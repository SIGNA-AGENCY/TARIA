# Treat Model

TARIA is designed with a security posture that assumes hostile environments, untrusted networks, and imperfect operators. The threat model focuses on reducing attack surface, eliminating high risk dependencies, and ensuring that failure states are safe, recoverable, and non catastrophic.

The primary threat addressed is platform level compromise common in traditional CMS systems. Database driven architectures introduce large attack surfaces through query layers, authentication systems, plugin ecosystems, and administrative dashboards. TARIA removes the database entirely. There is no query engine, no SQL interpreter, no schema, and no plugin runtime. This eliminates entire classes of injection, privilege escalation, and remote execution vulnerabilities.

Another major threat is unauthorized file system manipulation. During site creation, the builder blocks symbolic links while copying templates to prevent directory traversal and link based privilege attacks. All directory creation is controlled and permission limited. Temporary build directories are used for all site creation, and only atomic renaming is allowed into the live environment. This prevents partial or corrupted deployments and reduces the risk of race condition exploits.

Credential security is addressed by hashing all stored passwords using standard password hashing functions. Plain text credentials are never written to disk. Input validation is enforced at every step of conversational flows, including strict naming rules and reserved word blocking for site identifiers. Invalid states are rejected early and do not propagate into file operations.
Session handling is limited in scope and used only to track conversational flow state. There is no persistent session store containing sensitive content data. Once a build flow completes or fails, session data is cleared. This limits session fixation and leakage risk.

TARIA assumes that tracking scripts and third party analytics create surveillance and data leakage risk. Therefore no external tracking or advertising scripts are used. Analytics are native and privacy controlled. Media metadata is stripped automatically to prevent accidental disclosure of device identity or location data.

Data loss and censorship are treated as real world threats. Every site is stored as portable files. Backups are trivial. Export is always available. A site can be erased quickly if required. Optional onion distribution and Tor friendly deployment allow operation in restricted networks. This protects against platform takedowns, hosting provider interference, and geographic censorship.

Operational simplicity is also a security strategy. Fewer moving parts means fewer unknown failure modes. No background workers. No plugin loaders. No database daemons. No external service dependencies. The runtime is predictable, inspectable, and easy to recover.

The result is a system designed to fail safely, recover quickly, resist common web exploitation techniques, and remain functional under hostile or unstable conditions.

In short, TARIA reduces security risk not by adding layers of defense, but by removing unnecessary complexity.

– Arash Giani
