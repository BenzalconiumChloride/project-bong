<!-- Footer -->
<footer class="py-5">
	<div class="container">
		<div class="row g-4 mb-4">
			<div class="col-md-3">
				<div class="d-flex align-items-center mb-3">
					<div class="logo-box me-2" style="width: 32px; height: 32px;">
						<i class="bi bi-stars text-white" style="font-size: 18px;"></i>
					</div>
					<span class="fw-bold fs-5 text-white">LearnHub</span>
				</div>
				<p class="small">
					Making learning fun and accessible for students from Grade 1 to Grade 12.
				</p>
			</div>
			<div class="col-md-3">
				<h6 class="fw-semibold text-white mb-3">Features</h6>
				<ul class="list-unstyled">
					<li class="mb-2"><a href="#">AI Chatbot</a></li>
					<li class="mb-2"><a href="#">Learning Games</a></li>
					<li class="mb-2"><a href="#">Health & Safety</a></li>
				</ul>
			</div>
			<div class="col-md-3">
				<h6 class="fw-semibold text-white mb-3">Portals</h6>
				<ul class="list-unstyled">
					<li class="mb-2"><a href="#">Teacher Portal</a></li>
					<li class="mb-2"><a href="#">Student Portal</a></li>
				</ul>
			</div>
			<div class="col-md-3">
				<h6 class="fw-semibold text-white mb-3">Support</h6>
				<ul class="list-unstyled">
					<li class="mb-2"><a href="#">Help Center</a></li>
					<li class="mb-2"><a href="#">Contact Us</a></li>
					<li class="mb-2"><a href="#">Privacy Policy</a></li>
				</ul>
			</div>
		</div>
		<div class="border-top border-secondary pt-4 text-center">
			<p class="small mb-0">&copy; 2026 LearnHub. All rights reserved.</p>
		</div>
	</div>
</footer>
<!-- Floating Chatbot -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050;">
	<!-- Chat Window -->
	<div class="chat-window mb-3 d-none" id="chatWindow">
		<div class="chat-header d-flex align-items-center justify-content-between">
			<div class="d-flex align-items-center">
				<div class="bg-white rounded-circle p-2 me-2">
					<i class="bi bi-robot text-primary fs-5"></i>
				</div>
				<div>
					<h6 class="mb-0 fw-bold">BONG AI</h6>
					<small class="opacity-75">Always here to help!</small>
				</div>
			</div>
			<button class="btn btn-link text-white p-1" onclick="toggleChat()">
				<i class="bi bi-x-lg"></i>
			</button>
		</div>

		<div class="chat-messages" id="chatMessages">
			<div class="d-flex align-items-start mb-3">
				<div class="rounded-circle p-2 me-2" style="background: var(--blue-gradient);">
					<i class="bi bi-robot text-white"></i>
				</div>
				<div class="message-bubble bot">
					<p class="mb-0 small">Hi! I'm your AI Learning Assistant! 👋 How can I help you today?</p>
				</div>
			</div>
		</div>

		<div class="p-3 border-top">
			<div class="input-group">
				<input type="text"
					class="form-control border-0 bg-light"
					placeholder="Ask me anything..."
					id="chatInput"
					onkeypress="if(event.key==='Enter') sendMessage()">
				<button class="btn btn-gradient" onclick="sendMessage()">
					<i class="bi bi-send"></i>
				</button>
			</div>
		</div>
	</div>

	<!-- Chat Button -->
	<button class="chat-button" onclick="toggleChat()">
		<i class="bi bi-robot fs-4"></i>
		<span class="wave-animation position-absolute" style="top: -5px; right: -5px; font-size: 24px;">👋</span>
		<span class="badge-notification" id="chatBadge">1</span>
	</button>
</div>