<template>
  <v-card class="w-100 h-100 justify-center align-center d-flex flex-column">
    <div class="d-flex flex-column">
      <span class="text-h4">
        Ко<s>д</s>т 404
      </span>
    </div>
    <p class="text-h4">
      Такой страницы нет.
    </p>
    <p class="text-h3 primary--text mb-0">
      Зато есть кот 404, и он милый
    </p>
    <img src="@/assets/images/cat.jpg" style="max-width: 700px;">
  </v-card>
</template>

<script>

export default {
  name: "NotFoundPage",
};
</script>

<style lang="scss" src="./NotFoundPage.scss"/>