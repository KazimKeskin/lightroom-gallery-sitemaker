function setImage() {
      const fullImage = document.getElementById('fullImage')
      var i = window.countCurrent.textContent - 1
      console.log(i)
      fullImage.href = `images/full/${LR.images[i].exportFilename}.jpg`;
}

function toggleForm() {
      var form = document.getElementById("form");
      if (form.style.display === "block") {
        form.style.display = "none";
    document.getElementById("toggle").innerHTML = "Comment";
      } else {
        form.style.display = "block";
    document.getElementById("toggle").innerHTML = "Close";
      }
}

function toggleInfo() {
      var form = document.getElementById("loupeMeta");
      if (form.style.display === "block") {
        form.style.display = "none";
      } else {
        form.style.display = "block";
      }
}
