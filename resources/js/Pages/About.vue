<template>
  <div class="aboutcontainer">
    <h1>The Early History of the Codex</h1>
    <h2>A New Methodology and Ethics for Manuscript Studies (EthiCodex)</h2>
    <p>
      In the ancient Mediterranean world, literature had been written and copied 
      on scrolls for centuries, but something happened during the Roman imperial 
      period (circa 100-400 CE). The codex (that is, the book with pages) replaced 
      the scroll as the main vehicle for the transmission of literature. The development 
      of the technology of the codex marked a major change in the production and transmission 
      of knowledge. But when exactly did this change happen, and why?
    </p>
    <p>
      The Early History of the Codex is designed to place the study of the development 
      and spread of the codex on more reliable foundations. The project is funded by the 
      Research Council of Norway (2021-2026) and led by professor Brent Nongbri. The 
      project commenced in August 2021 and has emplyed two postdocs from 2022, Ariadne 
      Kostomitsopoulou Marketou and Robert Emil Berge.
    </p>
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
.aboutcontainer h1 {
  font-size: larger;
  margin-top: 1em;
}

.aboutcontainer h2 {
  font-size:medium;
}


.aboutcontainer {
  width: 60%;
  margin: auto;
}

.aboutcontainer p {
  margin-top: 1em;
}

</style>