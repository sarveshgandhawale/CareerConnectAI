/* =====================================================
   MOCK INTERVIEW
===================================================== */

let currentQuestion = 1;

const questions = [
    "Tell me about yourself and your background in technology.",
    "What is PHP and why is it commonly used for web development?",
    "What is the difference between GET and POST methods?",
    "Explain the difference between MySQL and SQL.",
    "What is object-oriented programming?",
    "What are your strengths as a developer?",
    "Tell me about a challenging project you have worked on.",
    "How do you handle errors in your application?",
    "Why should we hire you?",
    "Where do you see yourself in the next five years?"
];


/* =====================================================
   SPEECH RECOGNITION
===================================================== */

const SpeechRecognition =
    window.SpeechRecognition ||
    window.webkitSpeechRecognition;

let recognition = null;

if (SpeechRecognition) {

    recognition = new SpeechRecognition();

    recognition.lang = "en-US";

    recognition.continuous = false;

    recognition.interimResults = true;


    /* =================================================
       WHEN USER STARTS SPEAKING
    ================================================= */

    recognition.onstart = function () {

        const micCircle =
            document.getElementById("micCircle");

        const micText =
            document.getElementById("micText");

        const micSubText =
            document.getElementById("micSubText");

        const micButton =
            document.getElementById("micButton");

        const answerStatus =
            document.getElementById("answerStatus");


        if (micCircle) {
            micCircle.classList.add("listening");
        }

        if (micText) {
            micText.innerText = "Listening...";
        }

        if (micSubText) {
            micSubText.innerText =
                "Speak clearly and naturally";
        }

        if (micButton) {
            micButton.innerHTML =
                '<i class="fa-solid fa-stop"></i> Stop Answer';
        }

        if (answerStatus) {

            answerStatus.innerText =
                "Listening";

            answerStatus.style.background =
                "#fff0e1";

            answerStatus.style.color =
                "#ff7a00";
        }

    };


    /* =================================================
       WHEN USER SPEAKS
    ================================================= */

    recognition.onresult = function (event) {

        let transcript = "";

        for (
            let i = event.resultIndex;
            i < event.results.length;
            i++
        ) {

            transcript +=
                event.results[i][0].transcript;
        }


        const answerText =
            document.getElementById("answerText");

        if (answerText) {

            answerText.value =
                transcript;

            updateWordCount(transcript);
        }

    };


    /* =================================================
       WHEN SPEAKING ENDS
    ================================================= */

    recognition.onend = function () {

        const micCircle =
            document.getElementById("micCircle");

        const micText =
            document.getElementById("micText");

        const micSubText =
            document.getElementById("micSubText");

        const micButton =
            document.getElementById("micButton");

        const answerStatus =
            document.getElementById("answerStatus");


        if (micCircle) {
            micCircle.classList.remove("listening");
        }

        if (micText) {
            micText.innerText =
                "Answer recorded";
        }

        if (micSubText) {
            micSubText.innerText =
                "You can review your answer below";
        }

        if (micButton) {
            micButton.innerHTML =
                '<i class="fa-solid fa-microphone"></i> Start Again';
        }

        if (answerStatus) {

            answerStatus.innerText =
                "Recorded";

            answerStatus.style.background =
                "#ecfdf3";

            answerStatus.style.color =
                "#027a48";
        }

    };


    /* =================================================
       ERROR
    ================================================= */

    recognition.onerror = function (event) {

        console.log(
            "Speech recognition error:",
            event.error
        );


        const micCircle =
            document.getElementById("micCircle");

        const micText =
            document.getElementById("micText");

        const micSubText =
            document.getElementById("micSubText");


        if (micCircle) {
            micCircle.classList.remove("listening");
        }


        if (micText) {

            if (event.error === "not-allowed") {

                micText.innerText =
                    "Microphone permission denied";

            } else {

                micText.innerText =
                    "Microphone error";
            }
        }


        if (micSubText) {

            if (event.error === "not-allowed") {

                micSubText.innerText =
                    "Please allow microphone permission";

            } else {

                micSubText.innerText =
                    "Please try again";
            }
        }

    };

}


/* =====================================================
   START / STOP LISTENING
===================================================== */

function startListening() {

    if (!SpeechRecognition) {

        alert(
            "Speech Recognition is not supported in this browser. Please use Google Chrome."
        );

        return;
    }


    try {

        recognition.start();

    } catch (error) {

        console.log(
            "Recognition already running."
        );

    }

}


/* =====================================================
   WORD COUNT
===================================================== */

function updateWordCount(text) {

    const wordCount =
        document.getElementById("wordCount");

    if (!wordCount) {
        return;
    }


    if (text.trim() === "") {

        wordCount.innerText =
            "0 words";

        return;
    }


    const words =
        text
            .trim()
            .split(/\s+/)
            .filter(word => word.length > 0);


    wordCount.innerText =
        words.length + " words";

}


/* =====================================================
   CLEAR ANSWER
===================================================== */

function clearAnswer() {

    const answerText =
        document.getElementById("answerText");

    const wordCount =
        document.getElementById("wordCount");

    const micText =
        document.getElementById("micText");

    const micSubText =
        document.getElementById("micSubText");

    const answerStatus =
        document.getElementById("answerStatus");


    if (answerText) {
        answerText.value = "";
    }

    if (wordCount) {
        wordCount.innerText = "0 words";
    }

    if (micText) {
        micText.innerText =
            "Click to start answering";
    }

    if (micSubText) {
        micSubText.innerText =
            "Your microphone is ready";
    }

    if (answerStatus) {

        answerStatus.innerText =
            "Ready";

        answerStatus.style.background =
            "#ecfdf3";

        answerStatus.style.color =
            "#027a48";
    }

}


/* =====================================================
   NEXT QUESTION
===================================================== */

function nextQuestion() {

    const answerText =
        document.getElementById("answerText");

    if (!answerText) {
        return;
    }


    const answer =
        answerText.value.trim();


    if (answer === "") {

        alert(
            "Please answer the question before continuing."
        );

        return;
    }


    /* Last question */

    if (currentQuestion >= questions.length) {

        alert(
            "🎉 Interview completed! Your AI report will be generated."
        );

        return;
    }


    currentQuestion++;


    /* Question number */

    const currentQuestionElement =
        document.getElementById("currentQuestion");

    if (currentQuestionElement) {

        currentQuestionElement.innerText =
            currentQuestion;
    }


    /* Question */

    const questionText =
        document.getElementById("questionText");

    if (questionText) {

        questionText.innerText =
            questions[currentQuestion - 1];
    }


    /* Clear answer */

    answerText.value = "";


    const wordCount =
        document.getElementById("wordCount");

    if (wordCount) {

        wordCount.innerText =
            "0 words";
    }


    /* Reset microphone */

    const micText =
        document.getElementById("micText");

    const micSubText =
        document.getElementById("micSubText");

    const answerStatus =
        document.getElementById("answerStatus");

    if (micText) {

        micText.innerText =
            "Click to start answering";
    }

    if (micSubText) {

        micSubText.innerText =
            "Your microphone is ready";
    }

    if (answerStatus) {

        answerStatus.innerText =
            "Ready";

        answerStatus.style.background =
            "#ecfdf3";

        answerStatus.style.color =
            "#027a48";
    }


    /* Progress */

    const progress =
        (currentQuestion / questions.length) * 100;


    const progressFill =
        document.getElementById("progressFill");

    if (progressFill) {

        progressFill.style.width =
            progress + "%";
    }


    window.scrollTo({

        top: 0,

        behavior: "smooth"

    });

}


/* =====================================================
   AI QUESTION VOICE
===================================================== */

function speakQuestion() {

    const questionText =
        document.getElementById("questionText");


    if (!questionText) {
        return;
    }


    const question =
        questionText.innerText;


    if ("speechSynthesis" in window) {

        speechSynthesis.cancel();


        const speech =
            new SpeechSynthesisUtterance(question);


        speech.lang =
            "en-US";

        speech.rate =
            0.9;

        speech.pitch =
            1;


        speechSynthesis.speak(
            speech
        );

    } else {

        alert(
            "Text-to-Speech is not supported in this browser."
        );

    }

}


/* =====================================================
   EXIT INTERVIEW
===================================================== */

function exitInterview() {

    const confirmExit =
        confirm(
            "Are you sure you want to exit the interview?"
        );


    if (confirmExit) {

        window.location.href =
            "../dashboard.php";

    }

}