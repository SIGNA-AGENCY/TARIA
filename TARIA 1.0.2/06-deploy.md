# Deploy

TARIA is designed to deploy simply, predictably, and repeatably. Deployment avoids complex orchestration systems, database provisioning, and multi service dependency graphs. A running TARIA environment requires only a standard web server with PHP and a writable file system.

The core engine exists as a versioned template directory. When a new site is created, this template is cloned into a live node directory. This means every site carries its own complete runtime and theme. No shared runtime dependencies exist between sites. Upgrading or modifying the engine can be done by updating the template for future builds without breaking existing nodes.

The system requires only two writable directories. One holds the engine templates. The other holds live site nodes. Site creation is performed through atomic folder operations, ensuring that incomplete builds never appear in production. Because deployment is file based, rolling back changes is as simple as restoring a directory from backup.

There is no database to provision, no schema to migrate, and no background worker pool to configure. This reduces setup time and eliminates common deployment errors associated with stateful services. Configuration lives in files, not hidden service layers.

Static assets such as stylesheets, scripts, and media files can be cached or served through a content delivery network. The origin server primarily handles text rendering, which keeps compute requirements low and scaling straightforward. Multiple origin servers can be deployed behind a load balancer if required, with simple file synchronization providing consistency.

Backup and disaster recovery are direct file operations. A complete system snapshot is a directory archive. Restoring service is a matter of unpacking files on a new server. No data reconstruction or reindexing is required.

The deployment philosophy is minimal infrastructure, transparent behavior, and fast recovery. If a server fails, another can be brought online quickly. If a site must be moved, its directory is copied. If an update fails, the previous state is restored.

In short, TARIA deploys like static files, operates like an application, and recovers like a simple archive.

– Arash
