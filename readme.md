# TARIA

  ## TARIA
  <br>
  <strong>Words In. World Out.</strong>



  A privacy-first, file-based publishing engine. No databases. No plugins. No bloat.<br>
  Instant, portable, resilient publishing that feels like thinking out loud.


  <a href="https://taria.app"><strong>Live Site → taria.app</strong></a>
  ·
  <a href="https://github.com/SIGNA-AGENCY/TARIA/releases">Releases</a>
  ·
  <a href="#installation">Installation</a>
  ·
  <a href="#license">License</a>


## What is TARIA?

TARIA is a lightweight publishing engine built for speed, ownership, and simplicity.

- Content lives as plain JSON files + Markdown blocks
- Zero database (no MySQL, no migrations, no query overhead)
- PHP-powered rendering with predictable performance (<300ms publishes)
- Assets offloaded to Bunny CDN → origin stays text-only and lean
- Multisite by design: isolated sites, shared immutable engine
- Terminal-first CLI + minimalist dashboard for real writers
- Privacy by construction: no cookies for visitors, server-side analytics only

Publishing should not feel like operating software.  
TARIA removes the friction so you can go from idea to live in seconds.

## Core Principles

- **File-first, database-free** → Everything is files. Portable and resilient.
- **Capability reduction over control** → Dangerous features are removed, not managed.
- **Privacy by construction** → No third-party scripts, no tracking pixels.
- **Performance as baseline** → Flat files + CDN = blink-fast loads.
- **Ownership first** → Export anytime. Leave anytime. No lock-in.

## Features

- Blogging, static pages, galleries, podcasts (RSS-ready)
- Block-based editor with Markdown support (no WYSIWYG bloat)
- Ecommerce basics (Stripe, PayPal, Bitcoin – no platform fees)
- Privacy-first analytics (aggregated, server-side only)
- RTL language support
- Media sanitization + metadata stripping
- Atomic deployments, versioned engine, instant rollbacks
- CLI commands for site creation, publish, health checks

## Quick Start (Local Development)

1. Clone the repo

```bash
git clone https://github.com/SIGNA-AGENCY/TARIA.git
cd TARIA
