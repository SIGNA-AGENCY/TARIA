# Interface

TARIA uses a command line interface as its primary control surface. Instead of traditional dashboards, menus, and form based administration, the system is operated through typed commands inside a terminal style interface in the browser. This design keeps interaction direct, fast, and focused on intent rather than navigation.

The interface consists of a screen that displays system output and a single input field for commands. Users type instructions such as help, info, clear, or build. Each command is sent to the server as structured JSON. The server responds with structured output describing what text to display and what kind of input is expected next. This creates a continuous feedback loop between user and system.

Complex operations are handled through guided conversational flows. For example, creating a new site is performed by entering the build command. The system then asks for a site name, validates it, requests an email, enforces password rules, and confirms the final input. Each step is processed sequentially, and invalid input returns the user to the appropriate step with clear error messages. This replaces multi page forms with a single uninterrupted dialogue.

The CLI is not an aesthetic choice. It is an efficiency choice. Typing is the fastest method humans have for expressing structured intent. By centering the interface on text input and immediate system response, TARIA keeps creators in motion and minimizes cognitive overhead. The interface feels closer to speaking to the system than operating software.

Because every interaction is structured and stateless beyond its immediate flow, the CLI remains simple to maintain, easy to extend, and resistant to interface breakage. New commands can be added without redesigning screens. The system stays consistent as capabilities grow.

The result is an interface that disappears into workflow. You type intent. The system executes. The world updates.

– Arash Giani
