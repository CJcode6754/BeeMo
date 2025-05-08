function updateImageSources() {
    const reportsImage = document.getElementById("reportsImage");
    const workersImage = document.getElementById("workersImage");
    const ParamImage = document.getElementById("ParamImage");
    const HarvestCycleImage = document.getElementById("HarvestCycleImage");

    if (window.innerWidth <= 767) {
        reportsImage.src = "img/Web Img/ReportsMobile1.png";
        workersImage.src = "img/Web Img/WorkersMobile.png";
        ParamImage.src = "img/Web Img/ParamMobile3.png";
        HarvestCycleImage.src = "img/Web Img/HarvestMobile1.png";
    } else {
        reportsImage.src = "img/Web Img/Reports1.png";
        workersImage.src = "img/Web Img/Workers.png";
        ParamImage.src = "img/Web Img/Paramm.png";
        HarvestCycleImage.src = "img/Web Img/HarvestCycle1.png";
    }
}

// Run on load
updateImageSources();

// Run on resize
window.addEventListener("resize", updateImageSources);
