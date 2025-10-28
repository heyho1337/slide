import { Controller } from "@hotwired/stimulus"
import Swiper from '@swiper';

export default class extends Controller {
    static targets = ["container", "next", "prev"]

    connect() {
        this.swiper = new Swiper(this.containerTarget, {
            loop: true,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            effect: 'fade',
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            }
        });
    }

    disconnect() {
        if (this.swiper) {
            this.swiper.destroy()  // Clean up Swiper instance when the controller is disconnected
        }
    }

    next() {
        if (this.swiper) this.swiper.slideNext()
    }

    prev() {
        if (this.swiper) this.swiper.slidePrev()
    }
}