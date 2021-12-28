

var post = document.querySelector("#post")

var textarea = post.querySelector("#postvalue")

var preview = {
  img : post.querySelector("img"),
  title : post.querySelector("h3"),
  desc : post.querySelector("p"),
  link : post.querySelector("small > a")
}


textarea.onblur = function() {
    previewLink(new FormData(post))
}


async function previewLink(content) {
  var previewContainer = post.querySelector(".has-link-preview")
  var previewContent = await fetch('link.php',{
      body : content,
      method : "POST"
  }).then((res) => res.json())
  .finally((res) => {
    console.log("loading complete")
  });

  if(previewContent.res) {
    previewContainer.style.display = "flex"
    preview.title.textContent = previewContent.title
    preview.desc.textContent =  previewContent.desc.substr(0,100) + "..."
    preview.link.textContent = previewContent.link
    preview.link.href = previewContent.link
    preview.img.src = previewContent.img
  }else {
    previewContainer.style.display = "none"

  }
  

  console.log(previewContent)

}