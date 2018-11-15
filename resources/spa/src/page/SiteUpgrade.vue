<template>
  <div class="m-box-model m-aln-center m-pos-f">
    <header class="m-box m-aln-center m-head-top m-main m-bb1 m-lim-width">
      <div class="m-flex-grow1 m-flex-shrink1">
        <svg
          class="m-style-svg m-svg-def"
          @click="goBack">
          <use
            xmlns:xlink="http://www.w3.org/1999/xlink"
            xlink:href="#icon-back"/>
        </svg>
      </div>
      <div class="m-box m-flex-grow1 m-flex-shrink1 m-justify-center"/>
      <div class="m-flex-grow1 m-flex-shrink1"/>
    </header>

    <main class="m-box-model m-aln-center m-justify-center m-flex-grow1 m-main m-lim-width">
      <p style="font-size: 24px">
        <span
          class="rotate"
          style="display: inline-block; font-size: 28px; margin-right: 5px;vertical-align:middle">☢</span>
        <span>功能开发中, 敬请期待...</span>
      </p>
    </main>
  </div>
</template>

<script>
export default {
  name: "SiteUpgrade"
};
</script>
