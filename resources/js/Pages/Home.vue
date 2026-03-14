<template>
  <div class="homecontainer">
    <div class="leftimage">
    <img src="/front_page.jpg" height="640" />
    <br><a href="https://digital.bodleian.ox.ac.uk/objects/dd8ccc42-6edc-4074-85f7-0a404417587e/surfaces/6db41ef9-f384-4134-8b0d-e84990e95abc/">
      © Bodleian Libraries, University of Oxford. </a>
    <a href="https://creativecommons.org/licenses/by-nc/4.0/"> CC BY-NC 4.0</a>
    </div>
    <div class="homeparagraphs">
    <p>Welcome to the EthiCodex database of early codices. 
      This database provides both codicological 
      details and provenance histories for Greek and 
      Latin codices of the first five centuries of the common era.</p>

      <p>The material contained in the database ranges in date from the age of 
      the earliest known papyrus and parchment codices (perhaps the first 
      century CE) to the early sixth century CE. The conceptual endpoint for 
      the corpus is the Vienna Dioscorides, which contains a dedication 
      datable to about the year 512 CE. This is a corpus of about 2100 Greek 
      and Latin manuscripts written on papyrus or parchment.</p>

      <p>The goal is to create a searchable collection of codicological details 
      (page dimensions, binding data, ink color, etc.) as well as data 
      that allows us to say something about the modern histories of these 
      artifacts. How did they come to be in the libraries and museums where 
      they are found today? To this end, the database also contains 
      information about provenance. Details about transactions 
      (excavations, sales, donations) and the figures involved can be 
      found for many of the items in the database. Because of the fragmentary 
      state of many of the pieces and the patchiness of archival evidence for 
      provenance, not all details are available for every codex or codex 
      fragment.</p>

      <p>The database is very much a work in progress. Members of the EthiCodex 
      team continue to  fill in data, so statistics derived from the database 
      will not yet reflect the entire corpus.</p>


      <p>This database is an output of The Early History of the Codex: A New 
      Methodology and Ethics for Manuscript Studies (EthiCodex), a project 
      funded by the Research Council of Norway (2021-2026, project number 314240). 
      The project is based at the Centre for the Advanced Study of Religion at MF 
      Norwegian School of Theology, Religion, and Society.</p>
        <div class="bottomimages">
            <img src="/casr.jpg" width="350" />
      <img src="/nfr.png" width="350" />
      </div>
      <div>
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
  width: 95%;
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
  max-width: 720px;
}

.leftimage {
  font-size:smaller;
}

.bottomimages {
    display: flex;
}

.bottomimages img {
    width: 50%;          /* each image takes half */
    height: auto;        /* keep aspect ratio */
}

</style>