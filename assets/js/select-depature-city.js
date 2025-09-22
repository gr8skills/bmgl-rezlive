const depature = document.querySelector(".depature"),
selectBtn = depature.querySelector(".select-btn"),
searchInp = depature.querySelector("input"),
options = depature.querySelector(".options");

let countries = ["Accra | Ghana", "Kumasi | Ghana", "Dubai | UAE", "London | England", "Zanzibar | Tanzania", "Kigali | Rwanda"];

function addCountry(selectedCountry) {
    options.innerHTML = "";
    countries.forEach(country => {
        let isSelected = country == selectedCountry ? "selected" : "";
        let li = `<li onclick="updateName(this)" class="${isSelected}">${country}</li>`;
        options.insertAdjacentHTML("beforeend", li);
    });
}
addCountry();

function updateName(selectedLi) {
    searchInp.value = "";
    addCountry(selectedLi.innerText);
    depature.classList.remove("active");
    selectBtn.firstElementChild.innerText = selectedLi.innerText;
}

searchInp.addEventListener("keyup", () => {
    let arr = [];
    let searchWord = searchInp.value.toLowerCase();
    arr = countries.filter(data => {
        return data.toLowerCase().startsWith(searchWord);
    }).map(data => {
        let isSelected = data == selectBtn.firstElementChild.innerText ? "selected" : "";
        return `<li onclick="updateName(this)" class="${isSelected}">${data}</li>`;
    }).join("");
    options.innerHTML = arr ? arr : `<p class="mt-3 text-danger"><i class="fa-regular fa-warning"></i> Not found</p>`;
});

selectBtn.addEventListener("click", () => depature.classList.toggle("active"));