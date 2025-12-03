<template>
  <v-snackbar
    v-model="showSnackbar"
    :color="color"
    multi-line
    bottom
    :timeout="timer"
    elevation="24"
    tile
    style="white-space: pre-wrap;"
    width="auto"
    class="text-start"
    @mouseenter.native="stopInterval"
    @mouseleave.native="startInterval"
  >
    <v-icon>{{ icon }}</v-icon
    >{{ message }}

    <v-btn v-if="showRestoreButton" @click="emitRestoreOperation()"
      >Отменить</v-btn
    >

    <template v-slot:action="{ attrs }">
      <v-btn
        color="white"
        text
        v-bind="attrs"
        @click="closeButton"
        absolute
        top
        right
        ><v-icon>mdi-close</v-icon></v-btn
      >
    </template>

    <v-progress-linear
      absolute
      bottom
      :value="snackBarProgress"
      color="white"
    />
  </v-snackbar>
</template>

<script>
import { $eventBus } from "@/main.js";

export default {
  name: "Snackbar",
  data: () => ({
    showSnackbar: false,
    message: "",
    color: "success",
    icon: "mdi-check",
    timer: null,

    showRestoreButton: false,

    snackBarProgress: 100,
    interval: null,
    savedTimer: null
  }),
  watch: {
    showSnackbar(newVal) {
      if (newVal) {
        this.snackBarProgress = 100;
        this.syncProgressBar();
      } else {
        clearInterval(this.interval);
        this.snackBarProgress = 0;
      }
    }
  },
  methods: {
    emitRestoreOperation() {
      $eventBus.$emit("restoreOperation");
      this.showSnackbar = false;
      clearInterval(this.interval);
      this.interval = null;
    },
    stopInterval() {
      if (this.showSnackbar == true) {
        this.savedTimer = this.timer;
        this.timer = -1;
        clearInterval(this.interval);
      }
    },
    startInterval() {
      if (this.showSnackbar == true) {
        this.timer = this.savedTimer;
        this.showSnackbar = true;
        this.snackBarProgress = 100;
        this.syncProgressBar();
      }
    },
    syncProgressBar() {
      clearInterval(this.interval);
      const startTime = Date.now();
      const endTime = startTime + this.timer;

      this.interval = setInterval(() => {
        const now = Date.now();
        const remainingTime = endTime - now;

        this.snackBarProgress = (remainingTime / this.timer) * 100;

        if (remainingTime <= 0) {
          clearInterval(this.interval);
          $eventBus.$emit("deleteOperationFromDB");
          this.showSnackbar = false;
        }
      }, 100);
    },
    show(data, showRestoreButton = false) {
      clearInterval(this.interval);
      this.showSnackbar = false;

      this.message = data.message || "";
      this.color = data.color || "";
      this.icon = data.icon || "";
      this.timer = data.timer || "";

      this.$nextTick(() => {
        this.snackBarProgress = 100;
        this.showSnackbar = true;
        this.showRestoreButton = showRestoreButton;
      });
    },
    showError(message, showRestoreButton = false) {
      const data = {
        message: message,
        color: "red",
        icon: "mdi-alert",
        timer: 5000
      };
      this.show(data, showRestoreButton);
    },
    showInfo(message, showRestoreButton = false) {
      const data = {
        message: message,
        color: "info",
        icon: "mdi-information",
        timer: 3000
      };
      this.show(data, showRestoreButton);
    },
    close() {
      this.showSnackbar = false;
    },
    closeButton() {
      clearInterval(this.interval);
      $eventBus.$emit("deleteOperationFromDB");
      this.showSnackbar = false;
    }
  }
};
</script>

<style lang="scss" src="./Snackbar.scss" />
