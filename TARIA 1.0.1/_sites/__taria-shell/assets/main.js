// assets/main.js

const elOutput = document.getElementById("output");
const elCmd    = document.getElementById("cmd");
const elScreen = document.getElementById("screen");
const elStatus = document.getElementById("status");
const elPrompt = document.getElementById("promptLabel");

let busy = false;
let expect = null; // { mask: boolean, inputMode: "text"|"password", prompt?: string }

// ------------------------------
// UI helpers
// ------------------------------
function addLine(text, cls = "") {
  const div = document.createElement("div");
  div.className = `line ${cls}`.trim();
  div.textContent = text;
  elOutput.appendChild(div);
  scrollDown();
}

function clearOutput() {
  elOutput.innerHTML = "";
}

function scrollDown() {
  elScreen.scrollTop = elScreen.scrollHeight;
}

function setStatus(s) {
  elStatus.textContent = s;
}

function setPromptLabel(text) {
  // Expect "TARIA>" or "Password:" etc
  elPrompt.textContent = text || "TARIA>";
}

function applyExpect(nextExpect) {
  expect = nextExpect || null;

  // Prompt label (this is the key change)
  setPromptLabel(expect?.prompt || "TARIA>");

  // Input type
  elCmd.type = (expect?.inputMode === "password") ? "password" : "text";
}

// Echo what the user typed on the SAME prompt line
function echoCommand(cmd) {
  const label = (elPrompt.textContent || "TARIA>").trim();

  const isSensitive = (expect?.mask === true) || (elCmd.type === "password");
  const shown = isSensitive ? "*".repeat(cmd.length) : cmd;

  addLine(`${label} ${shown}`, "muted");
}

// ------------------------------
// API
// ------------------------------
async function sendCommand(command) {
  const res = await fetch("/__taria-shell/api/command.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ command })
  });

  if (!res.ok) throw new Error(`HTTP_${res.status}`);
  return res.json();
}

function renderResponse(data) {
  if (data?.action === "clear") {
    clearOutput();
  }

  if (Array.isArray(data?.lines)) {
    for (const line of data.lines) {
      if (typeof line === "string") addLine(line);
      else if (line && typeof line.text === "string") addLine(line.text, line.class || "");
    }
  } else {
    addLine("Invalid response.", "error");
  }

  applyExpect(data?.expect);
}

// ------------------------------
// Boot
// ------------------------------
addLine("TARIA", "muted");
addLine("Words in. World out.", "muted");
addLine("Type help for commands.", "muted");
setStatus("READY");
applyExpect({ inputMode: "text", mask: false, prompt: "TARIA>" });
elCmd.focus();

// ------------------------------
// Input
// ------------------------------
elCmd.addEventListener("keydown", async (e) => {
  if (e.key !== "Enter") return;
  e.preventDefault();

  if (busy) return;

  const cmd = elCmd.value.trim();
  if (!cmd) return;

  // instant local clear
  if (cmd.toLowerCase() === "clear") {
    elCmd.value = "";
    clearOutput();
    setStatus("READY");
    elCmd.focus();
    return;
  }

  elCmd.value = "";
  echoCommand(cmd);

  busy = true;
  setStatus("BUSY");

  try {
    const data = await sendCommand(cmd);
    renderResponse(data);
  } catch (err) {
    addLine("Service unavailable.", "error");
  } finally {
    busy = false;
    setStatus("READY");
    elCmd.focus();
  }
});
