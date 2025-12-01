<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Login - Gestor</title>
  <style>body{font-family:Arial;padding:20px;max-width:700px;margin:auto}input,button{width:100%;padding:8px;margin:6px 0}button{cursor:pointer}</style>
</head>
<body>
  <h1>Iniciar sesión</h1>
  <input id="email" placeholder="Email">
  <input id="password" type="password" placeholder="Contraseña">
  <button onclick="login()">Entrar</button>
  <p id="msg"></p>
<script>
const API = "/api";
async function login(){
  const res = await fetch(API + "/login", {
    method:"POST",
    headers: {"Content-Type":"application/json","Accept":"application/json"},
    body: JSON.stringify({email: email.value, password: password.value})
  });
  const data = await res.json();
  if (data.token) {
    localStorage.setItem("token", data.token);
    localStorage.setItem("user", JSON.stringify(data.user));
    msg.textContent = "Login OK";
    setTimeout(()=>location.href="/",700);
  } else {
    msg.textContent = JSON.stringify(data);
  }
}
</script>
</body>
</html>
