# TARIA

**Words in. World out.**

TARIA is a high-performance, flat-file publishing engine built for people who want to own their signal.  
No databases. No bloated frameworks. No page builders. No surveillance business model hiding in the walls.

Just content, structure, and speed.

Think of it as a modern CMS stripped down to its nervous system.

---

## Why TARIA exists

Most publishing platforms today are built backwards.  
They start with dashboards, plugins, trackers, and third-party dependencies.  
Content becomes the last concern.

TARIA flips that.

Content is the source of truth.  
Files are the database.  
The interface is a terminal.  
The output is the web.

Simple in concept. Powerful in execution.

---

## Core principles

- Flat-file architecture (JSON + filesystem)  
- No SQL, no external database  
- Performance-first rendering  
- Minimal surface area for security  
- Human-readable structure  
- Terminal-style control layer  
- Works on cheap hardware, scales on strong hardware  
- No lock-in  

If you can move a folder, you can migrate your entire site.

---

## Under the hood

- Linux + Nginx + PHP 8.5  
- Custom-compiled PHP (no database modules)  
- File-based content engine  
- CDN-ready output  
- Multi-site architecture  
- Zero vendor frameworks  

The stack is intentionally boring.  
The results are not.

---

## How publishing works

1. Content lives as JSON files  
2. Templates render content into pages  
3. Requests hit Nginx → PHP → rendered output  
4. CDN caches the edge  
5. Visitors get instant pages  

No queries.  
No ORM.  
No waiting.

---

## Interface

TARIA ships with a terminal-style control console.  
Commands instead of dashboards.  
Editing instead of clicking through twenty admin screens.

Example:

arash@taria:~$ edit page about
Opening content/pages/about.json


You always know what is happening.  
Nothing hides behind UI abstraction.

---

## Who it’s for

- Writers  
- Artists  
- Photographers  
- Indie publishers  
- Studios  
- Small teams  
- Anyone tired of CMS bloat  

If you care about your words, images, and identity — TARIA fits.

---

## Philosophy

Most platforms treat publishing as a product.  
TARIA treats publishing as infrastructure.

Quiet. Reliable. Durable.  
Like good typography or good architecture — it disappears, leaving only the message.

---

## Status

TARIA is active and evolving.  
The engine is live.  
Themes are being built.  
Tooling is expanding.

Early days. Solid foundation.

---

## License

Custom license.  
Use, modify, build upon.  
No resale of the core engine without permission.

(Full license text included in `/LICENSE`)

---

## Author

Built by **Arash Giani**  
Toronto, Canada  

Design. Systems. Signals.

---

## Final note

TARIA does not try to be everything.  
It tries to be the right thing.

**Words in. World out.**
