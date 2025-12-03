<template>
  <v-form class="elevation-0 pa-0 border-bottom w-100" :disabled="disabled">
    <div
      v-for="(operation, index) of operations"
      :key="operation.UID"
      :data-id="operation.UID"
    >
      <OperationFormItem
        ref="operationForms"
        :operation="operation"
        :focus="focusIndex === index"
        :disabled="disabled"
        @added-new-operation="
          $emit('added-new-operation', { index, operation })
        "
        @copied-operation="$emit('copied-operation', { index, operation })"
        @deleted-operation="$emit('deleted-operation', { index, operation })"
        @focus="
          focusOperation(index);
          $emit('focus', { index, operation });
        "
        @blur="unfocusOperation()"
      />

      <MoreDataBar
        v-if="operation.countMoreDataFields() > 1"
        :operation-number="operation.end_to_end_operation_number"
        :more-data="operation.MoreDataLongString"
      />

      <NextTPBar
        v-if="
          index + 1 !== operations.length &&
          operation.cipher_of_the_reference_tp !==
            operations[index + 1].cipher_of_the_reference_tp
        "
        :next-process="
          operations[index + 1].cipher_of_the_reference_tp ||
          main.cipher_main_TD.replace(/\s\/ (.*)/, '')
        "
      />
    </div>
  </v-form>
</template>

<script>
import { mapState, mapMutations } from "vuex";

import NextTPBar from "@/components/NextTPBar";
import MoreDataBar from "@/components/MoreDataBar";

import OperationFormItem from "./OperationFormItem";

export default {
  name: "OperationForm",
  components: {
    OperationFormItem,
    NextTPBar,
    MoreDataBar,
  },
  props: {
    focusIndex: Number,
    disabled: Boolean,
  },
  computed: {
    ...mapState("card", {
      main: (state) => state.card,
      operations: (state) => state.operations,
    }),
  },
  methods: {
    ...mapMutations("operation/focus", ["focusOperation", "unfocusOperation"]),
    async validation() {
      const validation = await Promise.all(
        this.$refs.operationForms.map((form) => form.validation)
      );
      return validation.every((v) => v === true);
    },
    validate() {
      for (const form of this.$refs.operationForms) {
        form.validate();
      }
    },
    resetValidation() {
      for (const form of this.$refs.operationForms) {
        form.resetValidation();
      }
    },
  },
};
</script>
