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
    document.getElementById("comment").innerHTML = "Comment";
      } else {
        form.style.display = "block";
    document.getElementById("comment").innerHTML = "Close";
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
