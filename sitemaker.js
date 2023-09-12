function setImage() {
      const fullImage = document.getElementById('fullImage')
      var i = window.countCurrent.textContent - 1
      console.log(i)
      fullImage.href = `images/full/${LR.images[i].exportFilename}.jpg`;
}

function toggleForm() {
      var form = document.getElementById("form");
      var commentbtn = document.getElementById("comment-button");
          commentbtn.classList.toggle("active");
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

function openMenu() {
  var x = document.getElementById("smallnav");
  if (x.style.display === "block") {
    x.style.display = "none";
  } else {
    x.style.display = "block";
  }
}

function toggleScheme() {
  var body = document.body;
  var header = document.getElementById("header");
  var footer = document.getElementById("footer");
  body.classList.toggle("dark");
  body.classList.toggle("light");
  header.classList.toggle("dark");
  header.classList.toggle("light");
  footer.classList.toggle("dark");
  footer.classList.toggle("light");
}
