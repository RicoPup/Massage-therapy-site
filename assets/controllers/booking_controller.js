import { Controller } from 'stimulus';

export default class extends Controller {
    connect() {
        let times = document.querySelectorAll('.time')
        let timeInput = document.getElementById('time-input')
        let serviceContainer = document.querySelector('.services-container')
        let summary = document.querySelector('#summary-container')
        let servicesSelect = serviceContainer.querySelector('#service')

        times.forEach(function(el){
            el.addEventListener('click', function (){
                times.forEach(function (el){
                    el.classList.remove('time--active')
                })
                el.classList.toggle('time--active')
                timeInput.value = el.dataset.value

                serviceContainer.style.opacity = '1'
                serviceContainer.querySelector('#service').disabled = false
                serviceContainer.querySelector('.input--submit').disabled = false
                serviceContainer.querySelector('.input--submit').classList.remove('disabled')

                summary.querySelector('#time').innerHTML = 'Start time: ' + el.dataset.value
                summary.querySelector('#duration').innerHTML = 'Duration: ' + servicesSelect.options[servicesSelect.selectedIndex].dataset.duration +' minutes'
                summary.querySelector('#title').innerHTML = 'Service: ' + servicesSelect.options[servicesSelect.selectedIndex].dataset.title
                summary.querySelector('#price').innerHTML = 'Price: £' + servicesSelect.options[servicesSelect.selectedIndex].dataset.price
            })
        })

        servicesSelect.addEventListener('change', function(){
            summary.querySelector('#duration').innerHTML = 'Duration: ' + servicesSelect.options[servicesSelect.selectedIndex].dataset.duration +' minutes'
            summary.querySelector('#title').innerHTML = 'Service: ' + servicesSelect.options[servicesSelect.selectedIndex].dataset.title
            summary.querySelector('#price').innerHTML = 'Price: £' + servicesSelect.options[servicesSelect.selectedIndex].dataset.price
        })
    }
}
