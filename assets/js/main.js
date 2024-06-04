// Mobile Navbar
function openNav() {
    document.getElementById("mySidenav").style.width = "100%";
  }
  
  function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
  }

// Home Carousel
$('.owl-carousel').owlCarousel({
    loop:true,
    margin:10,
    nav:true,
    dots: true,
    responsive:{
            0:{
                items:1
            },
            600:{
                items:3
            },
            1000:{
                items:4
            }
        }
    })

// Home Enquiry Form
new MultiSelectTag('programs', {
    rounded: true,    // default true
    shadow: false,      // default false
    placeholder: 'Select Programs',  // default Search...
    onChange: function(values) {
        console.log(values)
    }
})  // id

