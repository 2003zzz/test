<template>
  <v-card
    color="primary"
    class="w-100 mt-auto d-flex justify-start align-center px-3"
    style="height: 70px; position: sticky; bottom: 0;"
  >
    <v-select
      :value="value"
      @input="value => $emit('input', value)"
      :items="items"
      label="Статус КНВ"
      class="mt-7"
      style="max-width: 200px;"
      dark
      dense
      item-text="value"
      item-value="id_status"
      :disabled="disabled"
      :loading="loading"
    />
  </v-card>
</template>

<script>
import { mapState, mapActions } from "vuex";

export default {
  name: "CardStatusSelector",
  props: {
    value: Number,
    disabled: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    ...mapState("status", {
      items: state => state.statuses,
      loading: state => state.loading,
      error: state => state.error
    })
  },
  async created() {
    await this.getCardStatusList();
    if (this.error) {
      this.$root.snackbar.showError(this.error);
    }
  },
  methods: {
    ...mapActions("status", ["getCardStatusList"])
  }
};
</script>
