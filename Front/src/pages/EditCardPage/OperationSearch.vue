<template>
  <v-autocomplete
    :value="value"
    :rules="[rules.required]"
    label="Операция"
    class="min-field"
    return-object
    persistent-hint
    no-filter
    :items="items"
    :loading="loading"
    :hint="hint"
    @input.native="findOperation"
    @change="selectOperation"
    @focus="$emit('focus')"
    @blur="$emit('blur')"
  >
    <template v-slot:selection="{ item, index }">
      {{ item.value.p018 }}
    </template>
  </v-autocomplete>
</template>

<script>
import Api from "@/services/api";
import { debounce } from "debounce";

export default {
  name: "OperationSearch",
  props: {
    value: Object
  },
  data: () => ({
    hint: null,
    items: [],
    loading: false,
    rules: {
      required: value => (!!value.p014 && !!value.p018) || "Введите значение"
    }
  }),
  mounted() {
    this.initWithData(this.value);
  },
  methods: {
    initWithData(data) {
      if (data.p014 && data.p018) {
        this.hint = data.p014;
        this.items = this.formatItems([data]);
      }
    },
    formatItems(items) {
      return items.map(item => {
        return {
          text: `${item.p014} (${item.p018})`,
          value: item
        };
      });
    },
    findOperation: debounce(
      function(event) {
        const query = event.target.value;
        if (query.trim().length < 2) return;

        this.loading = true;
        this.hint = null;

        Api.searchOperations(query)
          .then(response => {
            this.items = this.formatItems(response.data);

            this.$root.logger.log(
              `Выполнен поиск операции по запросу ${query}. Результат: ${response.data}`
            );
          })
          .catch(error => {
            this.$root.snackbar.showError(
              `Ошибка выполнения операции: ${error}\nОбратитесь к разработчику или попробуйте еще раз`
            );
            this.$root.logger.log(
              `Ошибка при поиске операции по запросу ${query}: ${error}`
            );
          })
          .finally(() => (this.loading = false));
      },
      700,
      false
    ),
    selectOperation(operation) {
      if (!operation) {
        this.hint = null;
        this.$emit("input", {
          p014: "",
          p018: ""
        });
        return;
      }

      this.initWithData(operation.value);
      this.$emit("input", operation.value);
    }
  }
};
</script>
