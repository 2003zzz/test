<template>
  <v-form class="elevation-0 pa-0 border-bottom w-100" disabled>
    <div
      v-for="(operation, index) of operations"
      :key="operation.UID"
      :data-id="operation.UID"
    >
      <OperationFormItem :operation="operation" />

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
            main.cipher_main_td.replace(/\s\/ (.*)/, '')
        "
      />
    </div>
  </v-form>
</template>

<script>
import { mapState } from "vuex";

import NextTPBar from "@/components/NextTPBar";
import MoreDataBar from "@/components/MoreDataBar";

import OperationFormItem from "./OperationFormItem";

export default {
  name: "OperationForm",
  components: {
    OperationFormItem,
    NextTPBar,
    MoreDataBar
  },
  computed: {
    ...mapState("card", {
      main: state => state.card,
      operations: state => state.operations
    })
  }
};
</script>
<!-- 200317798195912 -->
