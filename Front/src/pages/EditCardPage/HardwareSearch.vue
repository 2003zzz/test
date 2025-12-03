<template>
  <v-autocomplete
    :value="value"
    label="Оборудование"
    class="min-field"
    return-object
    persistent-hint
    no-filter
    :items="items"
    :loading="loading"
    :hint="hint"
    @input.native="findHardware"
    @change="selectHardware"
    @focus="$emit('focus')"
    @blur="$emit('blur')"
  >
    <template v-slot:selection="{ item, index }">
      {{ item.value.p501 }}
    </template>
  </v-autocomplete>
</template>

<script>
import Api from "@/services/api";
import { debounce } from "debounce";

export default {
  name: "HardwareSearch",
  props: {
    value: Object
  },
  data: () => ({
    hint: null,
    items: [],
    loading: false
  }),
  mounted() {
    this.initWithData(this.value);
  },
  methods: {
    initWithData(data) {
      if (data.p451 && data.p501) {
        this.hint = data.p451;
        this.items = this.formatItems([data]);
      }
    },
    formatItems(items) {
      return items.map(item => {
        return {
          text: `${item.p451} (${item.p501})`,
          value: item
        };
      });
    },
    findHardware: debounce(
      function(event) {
        const query = event.target.value;
        if (query.trim().length < 2) return;

        this.loading = true;
        this.hint = null;

        Api.searchHardware(query)
          .then(response => {
            this.items = this.formatItems(response.data);

            this.$root.logger.log(
              `Выполнен поиск наименования оборудования по запросу ${query}. Результат: ${response.data}`
            );
          })
          .catch(error => {
            this.$root.snackbar.showError(
              `Ошибка выполнения операции: ${error}\nОбратитесь к разработчику или попробуйте еще раз`
            );
            this.$root.logger.log(
              `Ошибка при поиске наименования оборудования по запросу ${query}: ${error}`
            );
          })
          .finally(() => (this.loading = false));
      },
      700,
      false
    ),
    selectHardware(hardware) {
      if (!hardware) {
        this.hint = null;
        this.$emit("input", {
          p451: "",
          p501: ""
        });
        return;
      }

      this.initWithData(hardware.value);
      this.$emit("input", hardware.value);
    }
  }
};
</script>
