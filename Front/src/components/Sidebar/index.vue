<template>
  <v-navigation-drawer
    v-model="drawer"
    :mini-variant="mini"
    :mini-variant-width="66"
    :width="250"
    mobile-breakpoint="xs"
    app
  >
    <v-list class="pa-0">
      <template v-for="item of items">
        <v-list-item
          :key="item.title"
          :to="item.view"
          class="px-4"
          :class="{ 'router-link-active-custom': isActive(item.view) }"
          v-if="item.roles.includes(user.role)"
        >
          <v-tooltip right link>
            <template v-slot:activator="{ on }">
              <v-list-item-icon v-on="on">
                <v-icon class="primary--text text-h4">
                  {{ item.icon }}
                </v-icon>
              </v-list-item-icon>
            </template>
            <span>
              {{ item.title }}
            </span>
          </v-tooltip>
          <v-list-item-content>
            <v-list-item-title class="mt-1 text-subtitle-1">
              {{ item.title }}
            </v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </template>
    </v-list>
  </v-navigation-drawer>
</template>

<script>
import { mapState } from "vuex";

export default {
  name: "Sidebar",
  props: {
    drawer: Boolean,
    mini: Boolean
  },
  computed: {
    ...mapState(["user"])
  },
  data() {
    return {
      items: [
        {
          title: "Просмотр",
          icon: "mdi-layers",
          view: "/cards",
          roles: ["PTT05A", "PTT05A_1", "PTT05B", "PTT05B_1"]
        },
        {
          title: "Создание",
          icon: "mdi-plus",
          view: "/products",
          roles: ["PTT05A", "PTT05A_1"]
        },
        {
          title: "Просмотр версий КНВ",
          icon: "mdi-archive",
          view: "/archive",
          roles: ["PTT05A", "PTT05A_1"]
        }
      ]
    };
  },
  methods: {
    isActive(path) {
      if (path == "/cards" && this.$route.path.startsWith("/cards/edit")) {
        return false;
      }

      if (path == "/products" && this.$route.path.startsWith("/cards/edit")) {
        return true;
      }
      return this.$route.path.startsWith(path);
    }
  }
};
</script>

<style lang="scss" src="./Sidebar.scss" scoped />
