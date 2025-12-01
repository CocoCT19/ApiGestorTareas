<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Registro - Gestor</title>
  <style>body{font-family:Arial;padding:20px;max-width:700px;margin:auto}input,button,select,textarea{width:100%;padding:8px;margin:6px 0}button{cursor:pointer}</style>
</head>
<body>
<h1>Registrar usuario</h1>

<input id="name" name="name" placeholder="Nombre">
<input id="email" name="email" placeholder="Email">

<input id="password" type="password" name="password" placeholder="Contraseña">

<input id="password_confirmation" type="password" name="password_confirmation" placeholder="Confirmar contraseña">

<button onclick="register()">Registrarse</button>
<p id="msg"></p>

<script>
const API = "/api";
async function register(){
  const res = await fetch(API + "/register", {
    method: "POST",
    headers: {"Content-Type":"application/json","Accept":"application/json"},
    body: JSON.stringify({
      name: name.value, email: email.value,
      password: password.value, password_confirmation: password_confirmation.value
    })
  });
  const txt = await res.text();
  msg.textContent = txt;
  if(res.status===201) setTimeout(()=>location.href="/login",900);
}
</script>
</body>
</html>
