
var ciscoBubbleChat = (function () {
	var smHost = 'ECUSOCIALMINER1.ecu.edu';
	var widgetId = '3';

	var msgMustAcceptCert = 'Certificate must be accepted to start the conversation.';
	var msgAcceptCertButtonLabel = 'Accept Certificate';
	var msgCloseButtonLabel = 'Close';
	var msgWaitingCertAcceptance = 'Waiting for certificate acceptance.';
	var msgConnectivityIssues = 'We are experiencing connectivity issues. Try later.';

	var appId = 'cisco_bubble_chat';
	var appMargin = 15;
	var appUrl = 'https://' + smHost + '/ccp/ui/BubbleChat.html?host=' + smHost + '&wid=' + widgetId;
	var connectivityCheckUrl = 'https://' + smHost + '/ccp/ui/ConnectivityCheck.html';
	var messageEventListener;
	var addNoCacheQueryParam;
	return {
		showChatWindow: function (injectedData) {
			var logPrefix = 'CISCO_BUBBLE_CHAT: ';
			if (document.getElementById(appId)) {
				console.log(logPrefix + 'Not loading BubbleChat as it is already loaded');
				return;
			}

			var validateInjectedData = function(formData) {
				// browser compatible way to check whether it is an object with 10 fields and all the values are strings
				var result = true;
				if (formData && typeof formData === 'object' && formData.constructor === Object){
					var counter = 0;
					for (var key in formData) {
						if (!(typeof formData[key] === 'string' || formData[key] instanceof String)) {
							result = false;
							break;
						}
						counter++;
						if (counter > 10) {
							result = false;
							break;
						}
					}
				} else {
					result = false;
				}
				return result;
			};

			if (injectedData) {
				if (validateInjectedData(injectedData.formData)) {
					appUrl += '&injectedFormData=' + encodeURIComponent(JSON.stringify(injectedData.formData));
				} else {
					if (typeof injectedData.validationErrorCallback === 'function') {
						injectedData.validationErrorCallback();
					} else {
						console.log(logPrefix + 'Could not invoke validationErrorCallback as it is not a function');
					}
				}
			}

			var iframe = document.createElement('iframe');
			iframe.setAttribute('sandbox', 'allow-scripts allow-same-origin allow-forms allow-popups');
			iframe.setAttribute('id', appId);
			iframe.setAttribute('style', 'position: fixed; width: 312px; height: 410px; border: none; bottom: 0px; right: 0; z-index:999;');
			document.body.appendChild(iframe);
			var frameWindow = iframe.contentWindow ? iframe.contentWindow : iframe;
			var frameDoc = frameWindow.document;

			// Trigger a page load for iframe inline content loading to work in Firefox
			frameDoc.open();
			frameDoc.close();

			frameDoc.body.innerHTML = 	'<div id="secure-connectivity-check-container" style="position: fixed; width: 300px; height: 395px; ' +
										'bottom: 10px; right: 10px; font-family: Helvetica; font-size: 14px; color: #4F5051;' +
										'box-shadow: 0 0 3px #000; background: #fff; display: flex; flex-direction: column; display: none;">' +
											'<div style="height: 25%;"></div>' +
											'<div style="height: 25%; display: flex; align-items: flex-start; justify-content: center; text-align: center;">' +
												'<div style="padding: 0 15% 0 15%;">' +
													'<div id="secure-connectivity-check-msg"></div>' +
													'<a id="accept-cert-button" style="display:none; padding-top: 10px" href="#" onclick="acceptCertificate(); return void(0);">' +
														msgAcceptCertButtonLabel +
													'</a>' +
												'</div>' +
											'</div>' +
											'<div style="height: 25%; display: flex; align-items: flex-end; justify-content: center; text-align: center;">' +
												'<div style="padding: 0 15% 0 15%;">' +
													'<a href="#" onclick="window.parent.postMessage({messageType: \'unmount\'}, \'*\'); return void(0);">' +
														msgCloseButtonLabel +
													'</a>' +
												'</div>' +
											'</div>' +
											'<div style="height: 25%;"></div>' +
										'</div>';

			frameWindow.acceptCertificate = function () {
				frameDoc.getElementById('secure-connectivity-check-msg').innerHTML = msgWaitingCertAcceptance;
				frameDoc.getElementById('accept-cert-button').style.display = 'none';
				window.open(addNoCacheQueryParam(connectivityCheckUrl), 'SM_CERT_PAGE');
			};

			if (!addNoCacheQueryParam){
				addNoCacheQueryParam = function (url) {
					return url + (url.indexOf("?") === -1 ? '?' : '&') + 'nocache=' + new Date().getTime();
				}
			}

			if (!messageEventListener) {
				messageEventListener = function (event) {
					console.log(logPrefix + 'Received event from origin: ' + event.origin);
					console.log(logPrefix + 'Received event data: ' + JSON.stringify(event.data));
					switch (event.data.messageType) {
					case 'resize':
						document.getElementById(appId).style.height = event.data.height + appMargin + 'px';
						console.log(logPrefix + 'Successfully resized');
						break;
					case 'unmount':
						document.body.removeChild(document.getElementById(appId));
						window.removeEventListener('message', messageEventListener);
						console.log(logPrefix + 'Successfully unmounted BubbleChat and removed event listener for message');
						break;
					case 'bubblechat-cert-accepted':
						document.getElementById(appId).setAttribute('src', addNoCacheQueryParam(appUrl));
						console.log(logPrefix + 'Successfully validated certificate acceptance and loaded BubbleChat');
						break;
					default:
						console.log(logPrefix + 'Unknown message type');
					}
				};
			}

			window.addEventListener('message', messageEventListener);
			console.log(logPrefix + 'Event listener for message added');

			// Check HTTPS connectivity and show appropriate screen
			var showConnectivityIssue = function (message, showAcceptCertLink) {
				window.postMessage({ messageType: 'resize', height: 395 }, '*');
				frameDoc.getElementById('secure-connectivity-check-container').style.display = 'block';
				frameDoc.getElementById('secure-connectivity-check-msg').innerHTML = message;
				frameDoc.getElementById('accept-cert-button').style.display = showAcceptCertLink ? 'block' : 'none';
			};
			var xhr = new XMLHttpRequest();
			xhr.onreadystatechange = function () {
				if (this.readyState === 4) {
					console.log(logPrefix + 'Connectivity check status: ' + this.status);
					switch (this.status) {
					case 200:
						iframe.setAttribute('src', addNoCacheQueryParam(appUrl));
						break;
					case 0:
						showConnectivityIssue(msgMustAcceptCert, true);
						break;
					default:
						showConnectivityIssue(msgConnectivityIssues, false);
					}
				}
			};
			console.log(logPrefix + 'Checking connectivity to: ' + connectivityCheckUrl);
			xhr.open('GET', addNoCacheQueryParam(connectivityCheckUrl), true);
			xhr.send();
		}
	};
})();
