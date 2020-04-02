let numOfItemsChosen = 0;
let numOfCustomItems = 0;
let itemsChosen = [];

function showFoods(e) {
    // Prevent page from reloading
    e.preventDefault();

    // Call json file with foods and then display the data
    fetch("../database/food_database.json")
    .then(function (response) {
        return response.json();
    })
    .then(function (data) {
        displayData(data);
    })
    .catch(function (err) {
        console.log(err);
    });
}

// Display all the foods in the json file
function displayData(data) {
    let searchTextBox = document.getElementById("keyword");
    let queryItem = searchTextBox.value;
    queryItem = queryItem.trim().toLowerCase();
    let mainContainer = document.getElementById("search-list");

    // Clear any previous search result
    while (mainContainer.firstChild) {
        mainContainer.removeChild(mainContainer.firstChild);
    }

    // Create ul list and append to container
    let ul = document.createElement('ul');
    ul.setAttribute('id', 'ul-results');
    mainContainer.appendChild(ul);

    // Display everything if no input
    if (queryItem == '') {
        for (let i = 0; i < data.Sheet1.length; i++) {
            if (!itemsChosen.includes(data.Sheet1[i].Food_name)) {
                let liItem = document.createElement("li");
                let aElem = document.createElement("a");
                let link = document.createTextNode(data.Sheet1[i].Food_name); 
    
                aElem.setAttribute('onClick', `addItem(${data.Sheet1[i].undefined}, "${data.Sheet1[i].Food_name}")`);
                aElem.appendChild(link);
    
                liItem.setAttribute('id', data.Sheet1[i].undefined);
                liItem.appendChild(aElem);
    
                ul.appendChild(liItem);
            }
        }
    }

    // Filter items and display the filtered result
    else {
        let foundAName = false;

        // Display all the food names that contain the query name
        for (let i = 0; i < data.Sheet1.length; i++) {
            if (data.Sheet1[i].Food_name.toLowerCase().indexOf(queryItem) > -1 && !itemsChosen.includes(data.Sheet1[i].Food_name)) {
                let liItem = document.createElement("li");
                let aElem = document.createElement("a");
                let link = document.createTextNode(data.Sheet1[i].Food_name); 

                aElem.setAttribute('onClick', `addItem(${data.Sheet1[i].undefined}, "${data.Sheet1[i].Food_name}")`);
                aElem.appendChild(link);

                liItem.setAttribute('id', data.Sheet1[i].undefined);
                liItem.appendChild(aElem);

                ul.appendChild(liItem);
                foundAName = true;
            }
        }

        // TODO: If no results show, display message
        // if (foundAName == false) {
        //     let liItem = document.createElement("li");
        //     // liItem.onclick = addItem(data.Sheet1[i].undefined);
        //     liItem.innerHTML = "Sorry database doesn't have your food item";
        //     ul.appendChild(liItem);
        // }
    }
}

function addItem(itemNum, itemName) {
    let resultsArea = document.getElementById('eatenForm');
    createForm(itemName, resultsArea, itemNum);

    let newItem = document.getElementById(itemNum);
    newItem.setAttribute('onClick', 'removeItem(this)');
    itemsChosen.push(itemName);
}

function removeItem(elm) {
    elm.remove();
} 

function createForm(itemName, resultsArea, itemNum) {
    let labelElem = document.createElement("input");
    labelElem.setAttribute('name', `item${numOfItemsChosen}`);
    labelElem.setAttribute('type', 'text');
    labelElem.setAttribute('value', itemName);
    labelElem.required = true;

    let inputOZElement = document.createElement("input");
    inputOZElement.setAttribute('type', 'number');
    inputOZElement.setAttribute('name', `item${numOfItemsChosen}weight`);
    inputOZElement.setAttribute('placeholder', 'Enter weight in oz eaten...');
    inputOZElement.setAttribute('step', '0.01');
    inputOZElement.setAttribute('min', '0');
    inputOZElement.required = true;

    let dayEatenElem = document.createElement("select");
    dayEatenElem.setAttribute('name', `item${numOfItemsChosen}dayeaten`);
    let breakfastElem = document.createElement("option");
    let lunchElem = document.createElement("option");
    let dinnerElem = document.createElement("option");
    dayEatenElem.setAttribute('class', 'form-control');
    breakfastElem.innerHTML = "Breakfast";
    lunchElem.innerHTML = "Lunch";
    dinnerElem.innerHTML = "Dinner";
    dayEatenElem.appendChild(breakfastElem);
    dayEatenElem.appendChild(lunchElem);
    dayEatenElem.appendChild(dinnerElem);

    let linebreakElem = document.createElement("br");
    
    resultsArea.appendChild(labelElem);
    resultsArea.appendChild(inputOZElement);
    resultsArea.appendChild(dayEatenElem);
    resultsArea.appendChild(linebreakElem);

    numOfItemsChosen++;
}

function addCustomItem() {
    let resultsArea = document.getElementById('eatenForm');

    let foodItemElem = document.createElement("input");
    foodItemElem.setAttribute('type', 'text');
    foodItemElem.setAttribute('name', `custom${numOfCustomItems}`);
    foodItemElem.setAttribute('placeholder', 'Food name');
    foodItemElem.required = true;

    let inputOZElement = document.createElement("input");
    inputOZElement.setAttribute('type', 'number');
    inputOZElement.setAttribute('name', `custom${numOfCustomItems}weight`);
    inputOZElement.setAttribute('placeholder', 'Enter weight in oz eaten...');
    inputOZElement.setAttribute('step', '0.01');
    inputOZElement.setAttribute('min', '0');
    inputOZElement.required = true;

    let dayEatenElem = document.createElement("select");
    dayEatenElem.setAttribute('name', `custom${numOfCustomItems}dayeaten`);
    let breakfastElem = document.createElement("option");
    let lunchElem = document.createElement("option");
    let dinnerElem = document.createElement("option");
    dayEatenElem.setAttribute('class', 'form-control');
    breakfastElem.innerHTML = "Breakfast";
    lunchElem.innerHTML = "Lunch";
    dinnerElem.innerHTML = "Dinner";
    dayEatenElem.appendChild(breakfastElem);
    dayEatenElem.appendChild(lunchElem);
    dayEatenElem.appendChild(dinnerElem);

    let linebreakElem = document.createElement("br");
    
    resultsArea.appendChild(foodItemElem);
    resultsArea.appendChild(inputOZElement);
    resultsArea.appendChild(dayEatenElem);
    resultsArea.appendChild(linebreakElem);
    
    numOfCustomItems++;
}