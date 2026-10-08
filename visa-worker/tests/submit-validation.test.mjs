import assert from "node:assert/strict";
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
  assert.equal(categories[3].items.at(-1), "other_documents");
  assert.equal(docs.other_documents[3], 1);
  assert.equal(supported.other_documents[3], true);
});

test("missing applicant name is rejected before creating a record", async () => {
  const result = await submit({ full_name: "" });
  assert.equal(result.status, 422);
  assert.equal(result.body.ok, false);
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
