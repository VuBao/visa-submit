export default {
  async fetch(request, env) {
    const url = new URL(request.url);

    if (!url.pathname.startsWith("/api/")) {
      return env.ASSETS.fetch(request);
    }

    // This Worker is behind Cloudflare Access. Forward the verified identity
    // internally to the private API Worker instead of making the browser
    // authenticate again on a second workers.dev hostname.
    const identity = request.headers.get("Cf-Access-Authenticated-User-Email");
    if (!identity) {
      return new Response(JSON.stringify({ error: "Cloudflare Access authentication required." }), {
        status: 401,
        headers: { "content-type": "application/json; charset=utf-8" },
      });
    }

    const headers = new Headers(request.headers);
    headers.set("Cf-Access-Authenticated-User-Email", identity);
    const upstream = new Request(
      `https://k-anhjobs-visa-submit.kanh-cv.workers.dev${url.pathname}${url.search}`,
      {
        method: request.method,
        headers,
        body: request.method === "GET" || request.method === "HEAD" ? undefined : request.body,
      },
    );
    return env.VISA_API.fetch(upstream);
  },
};
