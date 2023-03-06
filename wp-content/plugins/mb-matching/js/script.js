window.addEventListener('load', () => {
    const metaBoxItems = document.querySelectorAll('.mb-matching__grid-right .mb-matching__fields-item');
    const matchingCtn = document.querySelector('.mb-matching__grid-left .mb-matching__fields-ctn');
    const submitBtn = document.querySelector('.mb-matching__btn');
    const addBtn = document.querySelector('.add-btn');
    const addInput = document.querySelector('.add-input');
    const deleteBtns = document.querySelectorAll('.mb-matching__delete-btn')

    initDeleteBtn(deleteBtns)

    // console.log(metaBoxItems);
    // console.log(matchingCtn);
    // console.log(submitBtn);    
    
    submitBtn.addEventListener('click', () => {
        matchingCtn.submit()
    })

    addBtn.addEventListener('click', () => {
        const value = addInput.value;
        if (value.length > 0) {
            addItem(value);
            addInput.value = '';
        }
    })
    
    // console.log(metaBoxItems);
    metaBoxItems.forEach((item) => {
        item.addEventListener('click', (e) => {
            
            if (item.classList.contains('active')) {
                
                let value = e.target.querySelector("span").innerHTML
                let matchingFieldsItem = document.querySelector(`.mb-matching__fields-item[data-name='${value}']`);
                matchingFieldsItem.remove()
                item.classList.toggle('active')
                return
            }

            // console.log('test');
            

            item.classList.toggle('active')

            const value = e.target.querySelector("span").innerHTML

            addItem(value);
            
            // <div class="mb-matching__delete-btn">X</div>
            
            const deleteBtns = document.querySelectorAll('.mb-matching__delete-btn')

            // console.log(deleteBtns);
            initDeleteBtn(deleteBtns)
            
        })
    })

    function initDeleteBtn (deleteBtns) {
        deleteBtns.forEach((deleteBtn) => {
            deleteBtn.addEventListener('click', (e) => {
                const parent = e.target.parentNode;
                const labelValue = parent.querySelector('label').innerHTML
                console.log(labelValue);
                let metaBoxField = document.querySelector(`.mb-matching__grid-right .mb-matching__fields-item[data-name='${labelValue}']`);
                console.log(metaBoxField);
                if (metaBoxField !== null) {
                    metaBoxField.classList.toggle('active')
                } 
                parent.remove()
            })
        })
    }

    function addItem(value) {
        matchingCtn.innerHTML += `
            <div class="mb-matching__fields-item" data-name="${value}">
            <div>
            <label for="${value}">${value}</label>
            <input type="hidden" name="${value}" id="${value}" value="${value}">
            </div>
            <div class="mb-matching__input-ctn">
            <input type="number" name="${value}[weight][]${value}_weight_base" id="${value}_weight_base" placeholder="Poids en % de base" class="mb-matching__input">
            <input type="number" name="${value}[weight][]${value}_weight_essential" id="${value}_weight_essential" placeholder="Poids en % essentiel" class="mb-matching__input">
            <input type="text" name="${value}[sign]${value}_sign" id="${value}_sign" placeholder="Signe" class="mb-matching__input">
            </div>
            </div>`;
    }
})
