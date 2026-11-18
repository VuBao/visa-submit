import assert from "node:assert/strict";
import { pbkdf2Sync } from "node:crypto";
import { readFileSync } from "node:fs";
import test from "node:test";
import vm from "node:vm";
import worker from "../src/index.js";

const endpoint = "https://worker.example/api/visa/submit";
const fakeEnv = {
  DB: {
    prepare() {
      return {
        bind() {
          return { first: async () => null };
        },
      };
    },
  },
};

async function submit(extraFields = {}, fileField, file) {
  const body = new FormData();
  body.set("full_name", extraFields.full_name ?? "QA TEST");
  body.set("company_name", extraFields.company_name ?? "QA COMPANY");
  body.set("submission_id", "1234567890abcdef1234567890abcdef");
  if (fileField) body.set(fileField, file);
  const response = await worker.fetch(new Request(endpoint, { method: "POST", body }), fakeEnv);
  return { status: response.status, body: await response.json() };
}

test("all four frontend categories use document types supported by the Worker", () => {
  const frontend = readFileSync(new URL("../../visa-pages/assets/visa.js", import.meta.url), "utf8");
  const backend = readFileSync(new URL("../src/index.js", import.meta.url), "utf8");
  const admin = readFileSync(new URL("../../visa-admin/assets/admin.js", import.meta.url), "utf8");
  const docs = vm.runInNewContext(`(${frontend.match(/const docs = (\{[\s\S]*?\});\s*const categories/)[1]})`);
  const categories = vm.runInNewContext(`(${frontend.match(/const categories = (\[[\s\S]*?\]);\s*const documentGrid/)[1]})`);
  const supported = vm.runInNewContext(`(${backend.match(/const DOCUMENTS = (\{[\s\S]*?\});\s*const json/)[1]})`);
  const adminGroups = vm.runInNewContext(`(${admin.match(/const documentGroups = (\[[\s\S]*?\]);\s*const groupIndex/)[1]})`);
  assert.equal(categories.length, 4);
  const displayed = categories.flatMap((category) => category.items);
  assert.equal(new Set(displayed).size, displayed.length);
  assert.deepEqual([...displayed].sort(), Object.keys(docs).sort());
  for (const type of displayed)
    assert.ok(supported[type], `${type} is missing from the Worker`);
  for (let index = 0; index < categories.length; index++)
    assert.deepEqual(
      [...adminGroups[index].types].filter((type) => displayed.includes(type)),
      [...categories[index].items],
      `Admin group ${index + 1} differs from the applicant form`,
    );
  assert.equal(categories[3].items.at(-1), "health_check");
  assert.equal(docs.health_check[3], 1);
  assert.equal(supported.health_check[3], true);
  assert.ok(!displayed.includes("other_documents"));
  assert.equal(supported.other_documents[3], true, "old Other documents remain readable");
});

test("multi-file selection retains files across chooser, drag and paste rounds", () => {
  const frontend = readFileSync(new URL("../../visa-pages/assets/visa.js", import.meta.url), "utf8");
  const helpers = frontend.match(/  const sameFile = [\s\S]*?(?=  const setFiles = )/)?.[0];
  assert.ok(helpers, "file selection helpers are present");
  const mergeFiles = vm.runInNewContext(`(() => { ${helpers} return mergeFiles; })()`);
  const file = (name) => ({ name, size: 100, lastModified: 1, type: "image/jpeg" });
  const first = mergeFiles([], [file("a.jpg"), file("b.jpg")], true);
  const second = mergeFiles(first, [file("c.jpg")], true);
  assert.deepEqual(Array.from(second, (item) => item.name), ["a.jpg", "b.jpg", "c.jpg"]);
  assert.equal(mergeFiles(second, [file("b.jpg")], true).length, 3);
  assert.deepEqual(Array.from(mergeFiles(first, [file("c.jpg")], false), (item) => item.name), ["c.jpg"]);
});

test("Admin shows one matching document group and loads only its images", () => {
  const admin = readFileSync(new URL("../../visa-admin/assets/admin.js", import.meta.url), "utf8");
  const source = admin.match(/  function showDocumentGroup[\s\S]*?(?=  const esc =)/)?.[0];
  assert.ok(source, "Admin group navigation is present");
  const buttons = Array.from({ length: 4 }, (_, index) => ({
    dataset: { group: String(index) },
    classList: { toggle() {} },
    setAttribute(name, value) { this[name] = value; },
  }));
  const images = Array.from({ length: 4 }, (_, index) => ({ dataset: { src: `image-${index}` } }));
  const sections = Array.from({ length: 4 }, (_, index) => ({
    dataset: { group: String(index) },
    hidden: true,
    querySelectorAll() { return [images[index]].filter((image) => image.dataset.src); },
  }));
  let scrolled = false;
  const controls = {
    "#documentPrevious": { hidden: false },
    "#documentNext": { hidden: false },
    "#documentGroups": { scrollIntoView() { scrolled = true; } },
  };
  const context = {
    documentGroups: Array(4),
    document: { querySelectorAll(selector) { return selector.includes("#documentGroups") ? buttons : sections; } },
    $: (selector) => controls[selector],
    requestAnimationFrame: (callback) => callback(),
  };
  const showDocumentGroup = vm.runInNewContext(`(${source})`, context);
  showDocumentGroup(0);
  assert.deepEqual(sections.map((section) => section.hidden), [false, true, true, true]);
  assert.equal(images[0].src, "image-0");
  assert.equal(images[3].src, undefined);
  assert.equal(controls["#documentPrevious"].hidden, true);
  showDocumentGroup(3, true);
  assert.deepEqual(sections.map((section) => section.hidden), [true, true, true, false]);
  assert.equal(images[3].src, "image-3");
  assert.equal(controls["#documentNext"].hidden, true);
  assert.equal(buttons[3]["aria-selected"], "true");
  assert.equal(scrolled, true);
});

test("missing applicant name is rejected before creating a record", async () => {
  const result = await submit({ full_name: "" });
  assert.equal(result.status, 422);
  assert.equal(result.body.ok, false);
});

test("document deletion requires a valid application code, PIN and file ID", async () => {
  const response = await worker.fetch(
    new Request("https://worker.example/api/visa/delete-document", {
      method: "POST",
      headers: { "content-type": "application/json" },
      body: JSON.stringify({ application_code: "QA", pin: "123456", document_id: "bad" }),
    }),
    fakeEnv,
  );
  assert.equal(response.status, 422);
  assert.equal((await response.json()).ok, false);
});

test("deleting a saved document updates Drive, Sheet and D1", async () => {
  const originalFetch = globalThis.fetch;
  const pin = "123456";
  const salt = "qa-test-salt";
  const fileId = "file-12345678";
  const application = {
    application_code: "QA_08/10/2026",
    submission_id: "1234567890abcdef1234567890abcdef",
    pin_salt: salt,
    pin_hash: pbkdf2Sync(pin, salt, 100000, 32, "sha256").toString("base64url"),
    full_name: "QA TEST",
    company_name: "QA COMPANY",
    folder_id: "folder-12345678",
    created_at: "2026-10-08T00:00:00Z",
    documents_json: JSON.stringify([{ type: "other_documents", id: fileId, name: "qa.png", mimeType: "image/png" }]),
  };
  const requests = [];
  globalThis.fetch = async (url, init = {}) => {
    requests.push({ url: String(url), method: init.method || "GET", body: init.body });
    if (String(url).includes("oauth2.googleapis.com/token"))
      return Response.json({ access_token: "test-token" });
    if (String(url).includes("/drive/v3/files/"))
      return Response.json({ id: fileId, trashed: true });
    if ((init.method || "GET") === "GET")
      return Response.json({ values: [[], [application.application_code, application.created_at, application.full_name, application.company_name, "Mới / New", "submission_id:" + application.submission_id]] });
    return Response.json({ updatedCells: 42 });
  };
  try {
    const env = {
      GOOGLE_OAUTH_CLIENT_ID: "test-client",
      GOOGLE_OAUTH_CLIENT_SECRET: "test-secret",
      GOOGLE_OAUTH_REFRESH_TOKEN: "test-refresh",
      GOOGLE_SHEET_ID: "test-sheet",
      DB: {
        prepare(sql) {
          return {
            bind(...values) {
              return {
                first: async () => application,
                run: async () => {
                  if (sql.includes("SET documents_json")) application.documents_json = values[0];
                  return { meta: { changes: 1 } };
                },
              };
            },
          };
        },
      },
    };
    const response = await worker.fetch(new Request("https://worker.example/api/visa/delete-document", {
      method: "POST",
      headers: { "content-type": "application/json" },
      body: JSON.stringify({ application_code: application.application_code, pin, document_id: fileId }),
    }), env);
    const result = await response.json();
    assert.equal(response.status, 200);
    assert.deepEqual(result.application.documents, []);
    assert.equal(application.documents_json, "[]");
    assert.ok(requests.some((item) => item.method === "PATCH" && item.body === '{"trashed":true}'));
    assert.ok(requests.some((item) => item.method === "PUT" && String(item.body).includes("submission_id:")));
  } finally {
    globalThis.fetch = originalFetch;
  }
});

test("only an allowed Admin can reset a PIN, and only its hash is stored", async () => {
  const code = "QA_08/10/2026";
  const oldPin = "123456";
  const application = {
    application_code: code,
    pin_salt: "old-salt",
    pin_hash: pbkdf2Sync(oldPin, "old-salt", 100000, 32, "sha256").toString("base64url"),
    failed_attempts: 4,
    locked_until: 9999999999999,
  };
  const request = (email) => new Request(
    `https://worker.example/api/admin/applications/${encodeURIComponent(code)}/reset-pin`,
    { method: "POST", headers: email ? { "Cf-Access-Authenticated-User-Email": email } : {} },
  );
  const denied = await worker.fetch(request(), { ADMIN_EMAILS: "admin@example.com" });
  assert.equal(denied.status, 401);
  const outsider = await worker.fetch(request("outsider@example.com"), { ADMIN_EMAILS: "admin@example.com" });
  assert.equal(outsider.status, 401);

  const originalFetch = globalThis.fetch;
  const sqlCalls = [];
  globalThis.fetch = async (url) => {
    if (String(url).includes("oauth2.googleapis.com/token"))
      return Response.json({ access_token: "test-token" });
    if (String(url).includes("sheets.googleapis.com"))
      return Response.json({ values: [[], [code, "2026-10-08", "QA TEST", "QA COMPANY"]] });
    throw new Error("Unexpected external request in PIN reset test");
  };
  try {
    const env = {
      ADMIN_EMAILS: "admin@example.com",
      GOOGLE_OAUTH_CLIENT_ID: "test-client",
      GOOGLE_OAUTH_CLIENT_SECRET: "test-secret",
      GOOGLE_OAUTH_REFRESH_TOKEN: "test-refresh",
      GOOGLE_SHEET_ID: "test-sheet",
      DB: {
        prepare(sql) {
          return {
            bind(...values) {
              return {
                first: async () => application,
                run: async () => {
                  sqlCalls.push({ sql, values });
                  if (sql.includes("SET pin_salt")) {
                    application.pin_salt = values[0];
                    application.pin_hash = values[1];
                    application.failed_attempts = 0;
                    application.locked_until = null;
                  }
                  return { meta: { changes: 1 } };
                },
              };
            },
          };
        },
      },
    };
    const response = await worker.fetch(request("admin@example.com"), env);
    const result = await response.json();
    assert.equal(response.status, 200);
    assert.equal(response.headers.get("cache-control"), "no-store");
    assert.match(result.pin, /^\d{6}$/);
    assert.notEqual(result.pin, oldPin);
    assert.equal(application.pin_hash, pbkdf2Sync(result.pin, application.pin_salt, 100000, 32, "sha256").toString("base64url"));
    assert.equal(application.failed_attempts, 0);
    assert.equal(application.locked_until, null);
    assert.equal(sqlCalls.length, 1);
    assert.ok(!JSON.stringify(sqlCalls).includes(result.pin), "plaintext PIN must not be stored");
  } finally {
    globalThis.fetch = originalFetch;
  }
});

test("unknown document field is rejected instead of silently ignored", async () => {
  const file = new File(["fake"], "sample.png", { type: "image/png" });
  const result = await submit({}, "documents[unknown_type]", file);
  assert.equal(result.status, 422);
  assert.match(result.body.error, /không được hỗ trợ/);
});

for (const type of ["student_graduation", "student_transcript", "other_documents"]) {
  test(`${type} is recognized by the Worker`, async () => {
    const file = new File(["fake"], "sample.png", { type: "image/png" });
    const result = await submit({}, `documents[${type}]`, file);
    assert.equal(result.status, 422);
    assert.match(result.body.error, /file không hợp lệ/);
    assert.doesNotMatch(result.body.error, /không được hỗ trợ/);
  });
}

test("file above 10 MB is rejected", async () => {
  const file = new File([new Uint8Array(10 * 1024 * 1024 + 1)], "large.png", {
    type: "image/png",
  });
  const result = await submit({}, "documents[residence_card_front]", file);
  assert.equal(result.status, 422);
  assert.match(result.body.error, /10 MB/);
});

test("a PHP file is rejected", async () => {
  const file = new File(["<?php echo 1;"], "shell.php", { type: "application/x-php" });
  const result = await submit({}, "documents[residence_card_front]", file);
  assert.equal(result.status, 422);
  assert.match(result.body.error, /file không hợp lệ/);
});

test("a PDF with a false MIME signature is rejected", async () => {
  const file = new File(["not a pdf"], "sample.pdf", { type: "application/pdf" });
  const result = await submit({}, "documents[tax_certificate]", file);
  assert.equal(result.status, 422);
  assert.match(result.body.error, /file không hợp lệ/);
});
