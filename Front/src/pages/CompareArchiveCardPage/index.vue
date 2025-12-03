<template>
  <div class="v-card w-100 h-100 justify-start align-start d-flex flex-column">
    <PageLoadingDialog v-model="loading" />

    <ArchiveCardForm
      :main="firstForm"
      :compared="secondForm"
      @change="(version) => onChangeCardVersion(0, version)"
    />

    <ArchiveCardForm
      :main="secondForm"
      :compared="firstForm"
      @change="(version) => onChangeCardVersion(1, version)"
    />
  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";
import PageLoadingDialog from "@/components/PageLoadingDialog";
import ArchiveCardForm from "./ArchiveCardForm";

export default {
  name: "CompareArchiveCardPage",
  components: {
    PageLoadingDialog,
    ArchiveCardForm,
  },
  computed: {
    ...mapState("archive", {
      firstForm: (state) => state.forms[0],
      secondForm: (state) => state.forms[1],
      cardVersions: (state) => state.versions,
      currentVersion: (state) => state.currentVersion,
    }),
    cardID() {
      return this.$route.params.id;
    },
    loading() {
      return this.firstForm.loading || this.secondForm.loading;
    },
  },
  async mounted() {
    this.getCardStatusList();
    await this.getCardVersionList(this.cardID);
    this.getCardVersion({
      formIndex: 0,
      cardID: this.cardID,
    });
  },
  methods: {
    ...mapActions("archive", ["getCardVersionList", "getCardVersion"]),
    ...mapActions("status", ["getCardStatusList"]),
    onChangeCardVersion(formIndex, versionID) {
      this.getCardVersion({
        formIndex: formIndex,
        cardID: this.cardID,
        versionID: versionID,
      });
    },
  },
};
</script>
