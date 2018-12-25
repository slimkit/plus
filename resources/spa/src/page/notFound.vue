<template>
  <div
    class="not_found"
    @click="$router.go(-1)"
  >
    <svg viewBox="0 0 600 300">
      <!-- Symbol -->
      <symbol id="s-text">
        <text
          text-anchor="middle"
          x="50%"
          y="50%"
          dy=".35em"
        >
          404
        </text>
      </symbol>
      <!-- Duplicate symbols -->
      <use
        xlink:href="#s-text"
        class="text"
      />
      <use
        xlink:href="#s-text"
        class="text"
      />
      <use
        xlink:href="#s-text"
        class="text"
      />
      <use
        xlink:href="#s-text"
        class="text"
      />
      <use
        xlink:href="#s-text"
        class="text"
      />
      <text
        x="50%"
        y="290"
        class="tips_text"
      >
        {{ $t('network.not_found') }}
      </text>
    </svg>
  </div>
</template>
<script>
export default {
  name: 'NotFound',
}
</script>
<style lang='less' scoped>
.not_found {
  background: #eee;
  margin: 0;
  height: 100vh;
  width: 100%;
  font: 5rem/1 Open Sans, Impact;
  .tips_text {
    fill: #111;
    text-anchor: middle;
    text-align: center;
    font-size: 0.5rem;
    margin: auto;
  }
  svg {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    margin: auto;
  }
}

.text {
  fill: none;
  stroke-width: 4;
  stroke-linejoin: round;
  stroke-dasharray: 70 330;
  stroke-dashoffset: 0;
  -webkit-animation: stroke 6s infinite linear;
  animation: stroke 6s infinite linear;
}

.text:nth-child(5n + 1) {
  stroke: #f2385a;
  -webkit-animation-delay: -1.2s;
  animation-delay: -1.2s;
}

.text:nth-child(5n + 2) {
  stroke: #f5a503;
  -webkit-animation-delay: -2.4s;
  animation-delay: -2.4s;
}

.text:nth-child(5n + 3) {
  stroke: #e9f1df;
  -webkit-animation-delay: -3.6s;
  animation-delay: -3.6s;
}

.text:nth-child(5n + 4) {
  stroke: #56d9cd;
  -webkit-animation-delay: -4.8s;
  animation-delay: -4.8s;
}

.text:nth-child(5n + 5) {
  stroke: #3aa1bf;
  -webkit-animation-delay: -6s;
  animation-delay: -6s;
}

@-webkit-keyframes stroke {
  100% {
    stroke-dashoffset: -400;
  }
}

@keyframes stroke {
  100% {
    stroke-dashoffset: -400;
  }
}
</style>
