# Performance

TARIA is designed so that performance is not an optimization task but a natural outcome of architecture. The system removes the primary sources of latency found in traditional content platforms by eliminating databases, plugin execution chains, and heavy runtime frameworks. Every request resolves through simple file reads and deterministic template rendering.

Content is stored as structured JSON files. When a page is requested, the engine reads the relevant file from disk and renders it through a minimal template. There are no database queries, no object relational mapping layers, and no dynamic plugin hooks. This significantly reduces processing overhead and response time variance. The performance profile is predictable because there are few conditional execution paths.

The origin server is intentionally designed to serve mostly text. Static assets such as images, stylesheets, and scripts are served through a content delivery network. This reduces load on the origin, improves global latency, and allows horizontal scaling without complex infrastructure. Because asset delivery is separated from content rendering, bandwidth heavy operations do not interfere with application logic.

Site creation is handled through atomic file operations rather than runtime configuration. Once a site is built, no additional setup or dependency resolution occurs. This reduces cold start delays and removes background maintenance tasks common in database driven systems. Backups and exports are simple file copies rather than database dumps, further reducing operational overhead.

Caching is straightforward because content is deterministic. Rendered pages can be cached at multiple layers without worrying about dynamic session state or plugin side effects. This allows aggressive caching strategies while maintaining correctness.

The combined result is a system that produces extremely small response payloads, minimal server computation per request, and stable performance under load. The infrastructure requirements are low, scaling is linear, and failure modes are simple to diagnose.

In practical terms, pages appear rather than load.

– Arash
