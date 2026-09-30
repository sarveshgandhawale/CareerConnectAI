flowchart TD

    A[Student Registration / Login] --> B[Profile Creation]

    B --> C[Skill & Interest Assessment]

    C --> D[AI Career Analysis]

    D --> E[Career Recommendation & Personalized Roadmap]

    E --> F[Resume Upload]

    F --> G[AI Resume Analysis]

    G --> H[Mock Interview]

    H --> I[HR / Technical Interview]

    I --> J[Voice-Based Interaction]

    J --> K[AI Evaluation & Scoring]

    K --> L[Feedback & Improvement Suggestions]

    L --> M[Performance Tracking Dashboard]

    M --> N[Interview History & Progress]

    %% AI Integration
    D -.-> P[Gemini AI API]
    G -.-> P
    K -.-> P

    %% Database
    B --> Q[(MySQL Database)]
    C --> Q
    G --> Q
    K --> Q
    N --> Q
    Q --> M