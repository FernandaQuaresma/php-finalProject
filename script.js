let currentSlide = 0;
const slides = document.querySelectorAll('.slide'); // Seleciona todos os slides
const totalSlides = slides.length; // Conta o total de slides
const slider = document.querySelector('.slider'); // Pega o container dos slides

// Função para atualizar a posição dos slides
function updateSlidePosition() {
    slider.style.transform = `translateX(-${currentSlide * 100}%)`; // Move os slides
}

// Função para avançar para o próximo slide
function nextSlide() {
    currentSlide = (currentSlide + 1) % totalSlides; // Avança para o próximo slide
    updateSlidePosition();
}

// Função para voltar para o slide anterior
function prevSlide() {
    currentSlide = (currentSlide - 1 + totalSlides) % totalSlides; // Volta para o slide anterior
    updateSlidePosition();
}

// Configura o carrossel para avançar automaticamente a cada 5 segundos
setInterval(nextSlide, 5000); // Passa para a próxima notícia a cada 5 segundos

// Event listeners para os botões de navegação manual
document.querySelector('.next').addEventListener('click', nextSlide); // Avança com o botão "próximo"
document.querySelector('.prev').addEventListener('click', prevSlide); // Volta com o botão "anterior"

// Inicia a transição do carrossel logo que a página carregar
document.addEventListener('DOMContentLoaded', () => {
    updateSlidePosition();
});
