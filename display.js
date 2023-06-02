const form = document.querySelector('.formAddUrl');
const divDisplay = document.querySelector('.display');

window.addEventListener('load', async() => {
    const response = await fetch('pdo.php?displayData=1');
    const resultat = await response.text();

    console.log(resultat);
    
    divDisplay.innerHTML = "";
    divDisplay.innerHTML = resultat;
})

form.addEventListener('submit', async(e) => {
    e.preventDefault();
    const dataForm = new FormData(form)
    const promise = await fetch('pdo.php?displayData=1', {method: "POST", body: dataForm});
    const result = await promise.text();
    
    divDisplay.innerHTML = "";
    divDisplay.innerHTML = result;
})