<template>
  <v-dialog
    :value="value"
    @input="$emit('input')"
    width="500"
  >
    <v-form
      v-model="validation"
      ref="form"
      @submit.prevent=""
    >
      <v-card>
        <v-card-title>
          <span class="headline">
            Удалить выбранную операцию?
          </span>
        </v-card-title>
        <v-text-field
          v-model="changeReasonCipher"
          label="Шифр причины изменения"
          class="min-field mx-5"
          v-mask="'#'"
          :rules="[rules.changeReasonCipher]"
          v-on:keyup.enter="onCreate"
        />
        <v-divider/>
        <v-card-actions>
          <v-btn
            color="secondary darken-1"
            text
            tabindex="-1"
            @click="onClose"
          >
            Отменить
          </v-btn>
          <v-spacer/>
          <v-btn
            :disabled="!validation"
            color="primary"
            text
            @click="onCreate"
          >
            Удалить
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-form>
  </v-dialog>
</template>

<script>
export default {
  name: 'DeleteOperationDialog',
  props: {
    value: Boolean,
  },
  data: () => ({
    validation: false,
    changeReasonCipher: "",
    rules: {
      changeReasonCipher: value => /^[1-9]$/.test(value) || "Неверное значение"
    },
  }),
  watch: {
    value(newValue) {
      if (!newValue) {
        this.$refs.form.reset();
      }
    },
  },
  methods: {
    onClose() {
      this.$emit('input');
    },
    onCreate() {
      if (!this.validation) return;

      this.$emit('delete', this.changeReasonCipher);
      this.$emit('input');
    },
  },
}
</script>