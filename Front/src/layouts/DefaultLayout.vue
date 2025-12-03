<template>
  <v-layout v-if="!user.isLoading">
    <v-app-bar flat app style="height: 66px">
      <v-app-bar-nav-icon @click="toggleSidebar()" />
      <RouterLink to="/" class="ms-3">
        <img
          src="@/assets/images/logo/logo-white.png"
          alt="Rosatom Logo"
          class="d-block logo"
          width="110"
        />
      </RouterLink>
      <v-toolbar-title
        class="font-weight-bold text-uppercase ms-3 text-subtitle-1"
        id="page-title"
        flat
        >Главная страница</v-toolbar-title
      >
      <v-spacer />
      <a href="#" class="d-flex align-center text-decoration-none">
        <span class="me-3 text-subtitle-1 font-weight-bold">{{
          user.tabNum
        }}</span>
        <img :src="user.photo" alt="User Image" class="user" />
      </a>
      <a href="#" class="text-decoration-none ms-3">
        <v-icon class="text-h4">mdi-information-outline</v-icon>
      </a>
      <v-tooltip bottom>
        <template v-slot:activator="{ on, attrs }">
          <v-btn @click="toggleTheme" v-bind="attrs" v-on="on" icon>
            <v-icon class="text-h4">mdi-theme-light-dark</v-icon>
          </v-btn>
        </template>
        <span>Сменить тему</span>
      </v-tooltip>
    </v-app-bar>

    <Sidebar :drawer="drawer" :mini="mini" />

    <Snackbar ref="snackbar" />

    <v-main>
      <v-container fluid class="h-100">
        <keep-alive
          :include="[
            'MainPage',
            'SearchCardsPage',
            'SearchProductsPage',
            'SearchArchivePage'
          ]"
          :max="2"
        >
          <slot />
        </keep-alive>
      </v-container>
    </v-main>
  </v-layout>
</template>

<script>
import Sidebar from "@/components/Sidebar";
import Snackbar from "@/components/Snackbar";
import vuetify from "@/plugins/vuetify";
import { mapState } from "vuex";

export default {
  name: "DefaultLayout",
  components: {
    Sidebar,
    Snackbar
  },
  mounted() {
    this.$root.snackbar = this.$refs.snackbar;
  },
  computed: {
    ...mapState(["user"])
  },
  data() {
    return {
      drawer: true,
      mini: localStorage.getItem("sidebarMini") === "true"
    };
  },
  methods: {
    isMobileScreen() {
      return this.$vuetify.breakpoint.name === "xs";
    },
    toggleSidebar() {
      if (this.isMobileScreen()) {
        this.drawer = !this.drawer;
        this.mini = false;
      } else {
        this.drawer = true;
        this.mini = !this.mini;
        localStorage.setItem("sidebarMini", this.mini);
      }
    },
    toggleTheme() {
      vuetify.framework.theme.dark = !vuetify.framework.theme.dark;
      localStorage.setItem(
        "vuetify-theme",
        vuetify.framework.theme.dark ? "dark" : "light"
      );
    }
  }
};
</script>
