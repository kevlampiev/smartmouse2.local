let slider = new Vue({
  el: ".slider",
  delimiters: ["${", "}"],
  data: {
    slides: [],
    readyToSlide: true,
    currentIdx: 0,
    intervalID: null
  },
  methods: {
    // Вспомогательная функция возвращает куки с указанным name,
    // или undefined, если ничего не найдено
    getCookie(name) {
      let matches = document.cookie.match(
        new RegExp(
          "(?:^|; )" +
            name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, "\\$1") +
            "=([^;]*)"
        )
      );
      return matches ? decodeURIComponent(matches[1]) : undefined;
    },

    getData() {
      this.slides = JSON.parse(this.getCookie("slides"));

      this.slides.forEach(el => {
        el.imgFile = `img/forSlider/${el.imgFile}`;
      });
      // а можно сюда через fetch/axios
    },

    nextSlideRight() {
      if (this.readyToSlide) {
        this.readyToSlide = false;
        clearInterval(this.intervalID);
        let prevIndex = this.currentIdx;

        this.currentIdx =
          prevIndex == this.slides.length - 1 ? 0 : this.currentIdx + 1;

        this.slides[this.currentIdx].currentClass = "slider-item slide-left";
        this.slides[prevIndex].currentClass = "slider-item erase-left";
        setTimeout(() => {
          this.slides[this.currentIdx].currentClass = "slider-item";
          this.slides[prevIndex].currentClass = "slider-item hidden-slide";
          this.readyToSlide = true; // !!
          this.intervalID = setInterval(this.nextSlideRight, 10000);
        }, 1500);
      }
    },

    nextSlideLeft() {
      if (this.readyToSlide) {
        this.readyToSlide = false;
        clearInterval(this.intervalID);
        let prevIndex = this.currentIdx;

        this.currentIdx =
          prevIndex == 0 ? this.slides.length - 1 : this.currentIdx - 1;

        this.slides[this.currentIdx].currentClass = "slider-item slide-right";
        this.slides[prevIndex].currentClass = "slider-item erase-right";
        setTimeout(() => {
          this.slides[this.currentIdx].currentClass = "slider-item";
          this.slides[prevIndex].currentClass = "slider-item hidden-slide";
          this.readyToSlide = true; // !!
          this.intervalID = setInterval(this.nextSlideRight, 10000);
        }, 1500);
      }
    }
  },

  beforeMount() {
    this.getData();
    this.slides[0].currentClass = "slider-item";
    this.intervalID = setInterval(this.nextSlideRight, 10000);
    this.readyToSlide = true;
  }
});
