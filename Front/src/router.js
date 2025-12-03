import Vue from "vue";
import Router from "vue-router";
import store from "./store";

Vue.use(Router);

const router = new Router({
  mode: "history",
  base: process.env.BASE_URL,
  routes: [
    {
      path: "/",
      meta: {
        Title: process.env.VUE_APP_NAME + " - " + "Главная страница",
        layout: "DefaultLayout"
      },
      name: "MainPage",
      component: () => import("./pages/MainPage")
    },
    {
      path: "/cards",
      meta: {
        Title: process.env.VUE_APP_NAME + " - " + "Просмотр карт норм времени",
        roles: ["PTT05A", "PTT05A_1", "PTT05B", "PTT05B_1"],
        layout: "DefaultLayout"
      },
      name: "SearchCardsPage",
      component: () => import("./pages/SearchCardsPage")
    },
    {
      path: "/cards/show/:id([1-9][0-9]*)", // id > 0
      meta: {
        Title: process.env.VUE_APP_NAME + " - " + "Карта норм времени",
        roles: ["PTT05A", "PTT05A_1", "PTT05B", "PTT05B_1"],
        layout: "DefaultLayout"
      },
      name: "ShowCardPage",
      component: () => import("./pages/ShowCardPage")
    },
    {
      path: "/cards/view/:id([1-9][0-9]*)?",
      meta: {
        Title: process.env.VUE_APP_NAME + " - " + "Карта норм времени",
        roles: ["PTT05A", "PTT05A_1"],
        layout: "DefaultLayout"
      },
      name: "ViewCardPage",
      component: () => import("./pages/ViewCardPage")
    },
    {
      path: "/products/edit/:id([1-9][0-9]*)?",
      meta: {
        Title: process.env.VUE_APP_NAME + " - " + "Карта норм времени",
        roles: ["PTT05A", "PTT05A_1"],
        layout: "DefaultLayout"
      },
      name: "EditCardPage",
      component: () => import("./pages/EditCardPage")
    },
    {
      path: "/products/create/:id([1-9][0-9]*)?",
      meta: {
        Title: process.env.VUE_APP_NAME + " - " + "Карта норм времени",
        roles: ["PTT05A", "PTT05A_1"],
        layout: "DefaultLayout"
      },
      name: "CreateCardPage",
      component: () => import("./pages/CreateCardPage")
    },
    {
      path: "/products",
      meta: {
        Title: process.env.VUE_APP_NAME + " - " + "Изделия",
        roles: ["PTT05A", "PTT05A_1"],
        layout: "DefaultLayout"
      },
      name: "SearchProductsPage",
      component: () => import("./pages/SearchProductsPage")
    },
    {
      path: "/archive",
      meta: {
        Title: process.env.VUE_APP_NAME + " - " + "Просмотр архива",
        roles: ["PTT05A", "PTT05A_1"],
        layout: "DefaultLayout"
      },
      name: "SearchArchivePage",
      component: () => import("./pages/SearchArchivePage")
    },
    {
      path: "/archive/:id([1-9][0-9]*)?",
      meta: {
        Title: process.env.VUE_APP_NAME + " - " + "Карта норм времени (архив)",
        roles: ["PTT05A", "PTT05A_1"],
        layout: "DefaultLayout"
      },
      name: "CompareArchiveCardPage",
      component: () => import("./pages/CompareArchiveCardPage")
    },
    {
      path: "/logs",
      meta: {
        Title: process.env.VUE_APP_NAME + " - " + "Логи пользователей",
        roles: ["PTT05A", "PTT05A_1"],
        layout: "DefaultLayout"
      },
      name: "LogsPage",
      component: () => import("./pages/LogsPage")
    },
    {
      path: "*",
      meta: {
        Title: process.env.VUE_APP_NAME + " - " + "Страница не найдена",
        layout: "DefaultLayout"
      },
      name: "NotFoundPage",
      component: () => import("./pages/NotFoundPage")
    }
  ]
});

router.beforeEach((to, _from, next) => {
  if (to.meta.roles) {
    if (to.meta.roles.includes(store.getters["user/userRole"])) {
      next();
    } else if (to.name === "ViewCardPage") {
      next();
    } else {
      next("/404");
    }
  } else {
    next();
  }
});

export default router;
