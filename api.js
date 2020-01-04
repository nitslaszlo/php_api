// Az api.js fájlban hozzon létre olyan JavaScript kódot, ami a szervertől Ajax kéréssel
// lekérdezi, hogy ..., és a válaszüzenetben
// kapott ... adatot betölti a "valami" tagkijelölővel rendelkező bekezdésbe!
// A megfelelő szerver oldali végpont az alábbi jellemzőkkel bír:
// - Kérés típusa: GET
// - URL: http://localhost/api.php?action=getValami
// - Választípus: JSON
// - Válasz adat: {valami}

function getValami() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("valami").innerHTML = JSON.parse(this.responseText).valami;
            // Ezt nem kérte az érettségi mintafeladat:
            // console.log(JSON.parse(this.responseText).message);
        }
    }
    xhttp.open("GET", "http://localhost/api.php?action=getValami");
    xhttp.send();
}

// Írjon olyan kódot, ami a weboldal „...” szekciójában beírt mezők tartalmát elküldi
// a szervernek.
// - Kérés típusa: POST
// - URL: http://localhost/api.php?action=insert
// - Elküldött adat típusa: JSON
// - Elküldött adat: {mezo1, mezo2, mezo3}
// - Választípus: JSON
// - Válasz adat: {message}
// Amennyiben a küldés sikeres, törölje a weboldalon a mezők tartalmát és egy felugró ablakban
// jelenítse meg az alábbi üzenetet: „Köszönjük a bejegyzését!”.

function insertValami() {
    var xhttp = new XMLHttpRequest();
    var mezo1 = document.getElementById("inputMezo1");
    var mezo2 = document.getElementById("inputMezo2");
    var mezo3 = document.getElementById("inputMezo3");

    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            mezo1.value = "";
            mezo2.value = "";
            mezo3.value = "";
            // Ezt nem kérte a érettségi mintafeladat:
            // console.log(JSON.parse(this.responseText).message);
            alert("Köszönjük a bejegyzését!");
        }
    }
    xhttp.open("POST", "http://localhost/api.php?action=insert");

    // A PHP a JSON típusú tartalmat nem szereti, de rá lehet beszélni:
    xhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
    xhttp.send(JSON.stringify({ "mezo1": mezo1.value, "mezo2": mezo2.value, "mezo3": mezo3.value }));
    
    // Így szerencsésebb lenne, de az érettségin máshogyan kérik:
    // xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    // xhttp.send(`name=${name.value}&email=${email.value}&phone=${phone.value}&message=${message.value}`);
}

window.onload = function() {
    getValami();
}
