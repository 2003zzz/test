<template>
  <v-autocomplete
    :value="value"
    :rules="[rules.required]"
    label="Профессия"
    class="min-field"
    return-object
    persistent-hint
    no-filter
    :items="items"
    :loading="loading"
    :hint="hint"
    @input.native="findProfession"
    @change="selectProfession"
    @focus="$emit('focus')"
    @blur="$emit('blur')"
  >
    <template v-slot:selection="{ item, index }">
      {{ item.value.profcode70 }}
    </template>
  </v-autocomplete>
</template>

<script>
import Api from "@/services/api";
import { debounce } from "debounce";

export default {
  name: "ProfessionSearch",
  props: {
    value: Object
  },
  data: () => ({
    hint: null,
    items: [],
    loading: false,
    rules: {
      required: value =>
        (!!value.profcode70 && !!value.snm_as_scaption) || "Введите значение"
    }
  }),
  mounted() {
    this.initWithData(this.value);
  },
  methods: {
    initWithData(data) {
      if (data.profcode70 && data.snm_as_scaption) {
        this.hint = data.snm_as_scaption;
        this.items = this.formatItems([data]);
      }
    },
    formatItems(items) {
      return items.map(item => {
        return {
          text: `${item.profcode70} (${item.snm_as_scaption})`,
          value: item
        };
      });
    },
    findProfession: debounce(
      function(event) {
        const query = event.target.value;
        if (query.trim().length < 3) return;

        this.loading = true;
        this.hint = null;

        Api.searchProfessions(query)
          .then(response => {
            this.items = this.formatItems(response.data);

            this.$root.logger.log(
              `Выполнен поиск профессии по запросу ${query}. Результат: ${response.data}`
            );
          })
          .catch(error => {
            this.$root.snackbar.showError(
              `Ошибка выполнения операции: ${error}\nОбратитесь к разработчику или попробуйте еще раз`
            );
            this.$root.logger.log(
              `Ошибка при поиске профессии по запросу ${query}: ${error}`
            );
          })
          .finally(() => (this.loading = false));
      },
      700,
      false
    ),
    selectProfession(profession) {
      if (!profession) {
        this.hint = null;
        this.$emit("input", {
          profcode70: "",
          snm_as_scaption: "",
          type_of_profession_reference_book: ""
        });
        return;
      }

      this.initWithData(profession.value);
      this.$emit("input", profession.value);
    }
  }
};
</script>
