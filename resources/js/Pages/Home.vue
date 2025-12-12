<template>
  <div class="homecontainer">
    <img src="/storage/front_page_1.jpg" width="300" />
    <div class="homeparagraphs">
      <p>Welcome to the EthiCodex database of early codices. This database 
        provides both physical descriptive details and provenance
        histories for Greek and Latin codices of the first five 
        centuries of the common era.
      </p>
      <p>

      </p>
      <div class="bottomimages">
      <img src="/storage/casr.jpg" width="350" />
      <img src="/storage/nfr.png" width="350" />
    </div>
    </div>
  </div>

</template>

<script setup>

defineProps({
  test: {type: Boolean,
         default: false},
})

function toggle() {
  test = true;
  alert("TEst!")
}
</script>

<style>


.homecontainer {
  display: flex;
  flex-direction: row;
  width: 79%;
  padding-top: 20px;
  gap: 20px;
  margin: auto;
}

.homecontainer h1 {
  margin-top: 1em;
  font-size: larger;
}

.homeparagraphs {
  display: flex;
  flex-direction: column;
  gap: 10px;
  max-width: 700px;
}

.bottomimages {
  position: absolute;
  bottom: 100px;
}
</style>