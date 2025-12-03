<template>
  <v-app ref="app">
    <component :is="layoutInstance">
      <router-view />
    </component>
  </v-app>
</template>

<script>
import Logger from "./logger";

export default {
  name: "App",
  ref: "App",
  mounted() {
    this.$root.logger = Logger;
  },
  watch: {
    $route: function(to, from) {
      this.layout = this.$route.meta.layout;
      setTimeout(() => {
        document.title = to.meta.Title;
        document.getElementById("page-title").innerHTML = to.meta.Title.split(
          " - "
        )[1];
        this.$root.logger.log(
          `Переход со страницы ${from.meta.Title} (${from.fullPath}) на страницу ${to.meta.Title} (${to.fullPath})`
        );
      }, 0);
    }
  },
  computed: {
    layoutInstance() {
      if (!this.layout) {
        // this.layout = "DefaultLayout";
      }
      return () => import(`@/layouts/${this.layout}`);
    }
  },
  data() {
    return {
      layout: "DefaultLayout"
    };
  }
};
</script>

<style lang="scss">
.v-btn {
  white-space: normal !important;
}
.v-btn__content {
  letter-spacing: 0;
}

.v-btn--contained {
  .v-icon.v-icon {
    margin-top: -3px;
    margin-right: 3px;
    margin-left: 3px;
  }
}

.v-snack__content {
  .v-icon.v-icon {
    margin-top: -3px;
    margin-right: 10px;
    margin-left: 3px;
  }
}

.v-list-item__subtitle {
  font-size: 1rem;
}

/* для IE11 убираем трансформации -  Очень тормозные */
@media screen and (-ms-high-contrast: active),
  screen and (-ms-high-contrast: none) {
  .container--fluid {
    max-width: 98vw !important;
  }

  .v-application,
  .v-dialog,
  .v-dialog__content,
  .v-overlay__scrim,
  .v-btn,
  .v-btn__content,
  .v-responsive__sizer,
  .v-ripple,
  .v-ripple__container,
  .v-card,
  .v-card__content,
  .v-sheet,
  .v-sheet__content,
  .v-tooltip,
  .v-tooltip__content,
  a,
  .v-list,
  .v-list-item,
  .v-menu__content,
  .v-overlay,
  .v-text-field,
  .v-input__slot {
    transform-origin: unset !important;
    transform: none !important;
    transition: none !important;
  }

  .filelist_tooltip.v-tooltip__content a {
    transition: all 0.2s;
  }

  .v-ripple__container {
    display: none !important;
  }

  .v-input__slot {
    transition: none !important;
    transition-timing-function: unset !important;
  }

  /* исправления ошибки вывода v-data-footer в IE */
  .v-data-footer__select {
    flex-basis: auto;
  }

  .v-data-footer__select .v-select {
    flex-basis: 50px;
  }

  .v-data-footer__select .v-select .v-select__selections {
    flex-basis: auto;
  }

  /* конец блока исправления ошибки вывода v-data-footer в IE */

  /* прячем элементы, не работающие в IE */

  .hideInIE {
    display: none;
  }

  .v-select__selections {
    min-width: 50px !important;
  }

  .v-application h1 {
    line-height: normal !important; // иначе текст обрезался кое-где
  }

  .v-list-item {
    padding: 14px 16px 0;
  }

  .v-navigation-drawer__content {
    .v-list-item {
      padding: 0 16px !important;
    }
  }

  .v-application.theme--light {
    .v-form .v-label {
      background-color: #ffffff !important;
    }

    .v-form .v-label.theme--light {
      background-color: #ffffff !important;
    }

    .v-form .v-text-field .v-label {
      background-color: #fff !important;
    }
  }
}

.h-100 {
  height: 100% !important;
}

.w-100 {
  width: 100% !important;
}
</style>
