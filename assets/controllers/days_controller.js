import { Controller } from 'stimulus';

export default class extends Controller {
    connect() {
        let dayLabels = document.getElementById('therapist_days').querySelectorAll('label')
        console.log(dayLabels)
        dayLabels.forEach(function (el) {
            el.addEventListener('click', function () {
                el.classList.toggle('active')
                let d = document.querySelector('.' + el.getAttribute('for'))
                if (d.querySelectorAll('select')[0].disabled == true) {
                    d.querySelectorAll('label')[0].style.opacity = '1'
                    d.querySelectorAll('label')[1].style.opacity = '1'
                    d.querySelectorAll('select')[0].disabled = false
                    d.querySelectorAll('select')[1].disabled = false
                } else {
                    d.querySelectorAll('label')[0].style.opacity = '0.3'
                    d.querySelectorAll('label')[1].style.opacity = '0.3'
                    d.querySelectorAll('select')[0].disabled = true
                    d.querySelectorAll('select')[1].disabled = true
                }
                console.log("yay")
            })
        })
    }
}
