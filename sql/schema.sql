CREATE TABLE IF NOT EXISTS users (
    id bigint GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    username text NOT NULL UNIQUE,
    password_hash text NOT NULL,
    created_at timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS api_keys (
    id bigint GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    name text NOT NULL,
    api_key text NOT NULL UNIQUE,
    is_active boolean NOT NULL DEFAULT true,
    quota integer NOT NULL CHECK (quota >= 0),
    quota_usage integer NOT NULL DEFAULT 0 CHECK (quota_usage >= 0),
    created_at timestamptz NOT NULL DEFAULT now()
);

INSERT INTO users (username, password_hash)
VALUES ('admin', '$2y$12$0mQcJSH5MIFfLM9KD6b17uSe3lLUMDMPOZfZw9tZg27uFW1TKOrsa')
ON CONFLICT (username) DO NOTHING;

INSERT INTO api_keys (name, api_key, is_active, quota, quota_usage) VALUES
    ('OpenAI Production', 'llm_demo_openai_7f2a91c4', true, 100000, 100000),
    ('Claude Testing', 'llm_demo_claude_2b8d5e10', true, 50000, 50000),
    ('Gemini Backup', 'llm_demo_gemini_9c4f1a63', false, 25000, 25000)
ON CONFLICT (api_key) DO NOTHING;
