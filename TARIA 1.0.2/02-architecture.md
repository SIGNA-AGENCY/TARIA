# Architecture

TARIA is built as a conversational publishing engine that creates and manages websites through a command driven interface and a file based architecture. The system is designed to remove traditional CMS complexity by eliminating databases, plugin systems, and heavy administrative dashboards. Everything is controlled through typed commands and executed through simple, predictable server logic.

The user interface is a minimal terminal style application running in the browser. It consists of a screen for output and a single command input field. Users interact with TARIA by typing commands rather than navigating menus. When a command is entered, the browser sends it as structured JSON to the server. The server responds with structured instructions describing what to display and what input is expected next. This keeps interaction fast, direct, and focused on intent rather than interface.

On the server side, a command router receives all input. It parses commands, manages session state, and runs guided conversational flows. For complex operations, such as creating a new site, the router collects information step by step. It validates names, verifies email format, enforces password rules, and confirms inputs through dialogue. If an error occurs, the system safely returns the user to the appropriate step. This replaces traditional web forms with a controlled conversational state machine.

When a site creation flow is completed, the router calls the node builder. The builder validates the requested site name, blocks reserved or invalid names, and prepares a new site directory. It clones a versioned template directory into a temporary build folder, blocks symbolic links for safety, and writes a node configuration file containing metadata and a hashed password. Once the build is complete, the temporary directory is atomically renamed into the live sites directory. If any step fails, the builder cleans up automatically. If successful, it returns build statistics to the interface.

Every website in TARIA is represented as a folder on disk. Configuration lives in JSON files. Content is stored as structured files. Templates and engine code are copied from a versioned template source. There is no database server, no schema, and no query layer. The file system itself acts as the data layer. This makes backup, restore, migration, and export straightforward and reliable.

Atomic deployment ensures that partial site creation cannot occur. New sites are only moved into place once fully built. This prevents corruption and keeps the system stable even if interruptions occur during build processes. The overall security posture is strengthened by minimizing moving parts, blocking unsafe file operations, hashing credentials, and enforcing strict validation rules.

Once created, each site runs from the cloned template, which contains the rendering engine and theme. The runtime engine reads JSON content files and outputs web pages. Assets are served efficiently and can be cached by a CDN. Each site remains isolated in its own directory, making maintenance, scaling, and removal simple.

The result is a system where typed commands control a site factory, content lives as files, and publishing happens without databases, plugins, or complex administrative interfaces. Interaction is conversational. Infrastructure is minimal. Ownership and portability are built in by design.

Words in.
World out.

– Arash Giani