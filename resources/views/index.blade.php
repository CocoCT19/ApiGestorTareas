<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Panel - Gestor</title>
  <style>
    body{font-family:Arial;padding:20px;max-width:1000px;margin:auto}
    .row{display:flex;gap:12px}
    .col{flex:1}
    input,select,button,textarea{width:100%;padding:8px;margin:6px 0}
    button{cursor:pointer}
    .project{border:1px solid #ddd;padding:10px;margin:6px 0;border-radius:6px}
    .high{border-left:6px solid #e53e3e}
    .medium{border-left:6px solid #f6ad55}
    .low{border-left:6px solid #48bb78}
  </style>
</head>
<body>
  <h1>Mis Proyectos</h1>
  <div style="display:flex;gap:10px;align-items:center;">
    <input id="search" placeholder="Buscar por nombre">
    <select id="filterImportance">
      <option value="">Todas importancias</option>
      <option value="1">1 - Alta</option>
      <option value="2">2 - Media</option>
      <option value="3">3 - Baja</option>
    </select>
    <button onclick="loadProjects()">Buscar</button>
    <button onclick="logout()">Cerrar sesión</button>
  </div>

  <h3>Crear proyecto</h3>
  <input id="p_name" placeholder="Nombre del proyecto">
  <textarea id="p_desc" placeholder="Descripción"></textarea>
  <label>Importancia</label>
  <select id="p_importance">
    <option value="1">1 - Alta</option>
    <option value="2" selected>2 - Media</option>
    <option value="3">3 - Baja</option>
  </select>
  <button onclick="createProject()">Crear</button>
  <p id="status"></p>

  <h2>Lista</h2>
  <div id="list"></div>

<script>
const API = "/api";
let TOKEN = localStorage.getItem("token") || "";

function authHeaders() {
  return {
    "Content-Type":"application/json",
    "Accept":"application/json",
    "Authorization": "Bearer " + TOKEN
  };
}

async function loadProjects(){
  const q = search.value.trim();
  const imp = filterImportance.value;
  let url = API + "/projects";
  const params = [];
  if(q) params.push("q="+encodeURIComponent(q));
  if(imp) params.push("importance="+encodeURIComponent(imp));
  if(params.length) url += "?" + params.join("&");

  const res = await fetch(url, { headers: authHeaders() });
  if(res.status === 401 || res.status === 403) {
    status.textContent = "No autorizado. Haz login.";
    return;
  }
  const data = await res.json();
  renderList(data);
}

function renderList(projects){
  const el = document.getElementById("list");
  el.innerHTML = "";
  if(!projects.length) { el.innerHTML = "<i>No hay proyectos</i>"; return; }
  projects.forEach(p=>{
    const div = document.createElement("div");
    div.className = "project " + (p.importance==1? 'high' : (p.importance==2? 'medium':'low'));
    div.innerHTML = `
      <strong>${escapeHtml(p.name)}</strong>
      <p>${escapeHtml(p.description || '')}</p>
      <small>Importancia: ${p.importance} - ID: ${p.id}</small>
      <div style="margin-top:8px">
        <button onclick="deleteProject(${p.id})">Eliminar</button>
        <button onclick="openTasks(${p.id})">Ver tareas</button>
      </div>
      <div id="tasks-${p.id}"></div>
    `;
    el.appendChild(div);
  });
}

function escapeHtml(text) {
  return text
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;");
}

async function createProject(){
  const payload = {
    name: p_name.value.trim(),
    description: p_desc.value.trim(),
    importance: parseInt(p_importance.value,10)
  };
  const res = await fetch(API + "/projects", {
    method:"POST",
    headers: authHeaders(),
    body: JSON.stringify(payload)
  });
  if(res.status === 201) {
    status.textContent = "Proyecto creado ✔";
    p_name.value = ""; p_desc.value = ""; p_importance.value = "2";
    loadProjects();
  } else {
    const txt = await res.text();
    status.textContent = "Error: " + txt;
  }
}

async function deleteProject(id){
  if(!confirm("Eliminar proyecto?")) return;
  const res = await fetch(API + "/projects/" + id, {
    method:"DELETE",
    headers: authHeaders()
  });
  if(res.status === 204) {
    status.textContent = "Proyecto eliminado";
    loadProjects();
  } else {
    const txt = await res.text();
    status.textContent = "Error: " + txt;
  }
}

async function openTasks(projectId){
  const container = document.getElementById("tasks-"+projectId);
  container.innerHTML = "<i>Cargando tareas...</i>";
  const res = await fetch(API + "/projects/" + projectId, { headers: authHeaders() });
  if(res.status !== 200) { container.innerHTML = "Error al cargar"; return; }
  const data = await res.json();
  const html = data.tasks.map(t=>`<div style="padding:6px;border-top:1px dashed #eee">
    <b>${escapeHtml(t.title)}</b> - ${t.is_completed ? 'Completada' : 'Pendiente'}
  </div>`).join("");
  container.innerHTML = `<div>${html}</div>`;
}

// auth
function checkLogin() {
  TOKEN = localStorage.getItem("token") || "";
  if(!TOKEN) {
    status.textContent = "No estás logueado. Ve a /login";
  } else {
    status.textContent = "Autenticado.";
    loadProjects();
  }
}

function logout(){
  localStorage.removeItem("token");
  localStorage.removeItem("user");
  TOKEN = "";
  location.href = "/login";
}

checkLogin();
</script>
</body>
</html>
