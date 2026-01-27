const express = require("express");
const path = require("path");
const bodyParser = require("body-parser");
const cors = require("cors");
const mongoose = require("mongoose");
const jwt = require('jsonwebtoken');
mongoose.set('bufferTimeoutMS', 30000);  

const app = express();
app.use(express.json());
app.use(cors({ origin: "http://localhost:3000", methods: ["GET", "POST"], credentials: true }));


app.use(bodyParser.json()); 
app.use(bodyParser.urlencoded({ extended: true })); 
app.use(cors()); 

const MONGO_URI = "";


mongoose
  .connect(MONGO_URI, { retryWrites: true, w: "majority" })
  .then(() => console.log("Connected to MongoDB Atlas"))
  .catch((err) => console.error("Error connecting to MongoDB Atlas:", err));


const UserSchema = new mongoose.Schema({
  username: String,
  email: String,
  branch: String,
  password: String 
});

const UserModel = mongoose.model("User", UserSchema);


app.use(express.static(path.join(__dirname, "../build")));


app.post("/signup", async (req, res) => {
  const { username, email, branch, password, confirmPassword } = req.body;

  if (!username || !email || !branch || !password || !confirmPassword) {
    return res.status(400).json({ message: "Please fill all fields" });
  }

  if (password !== confirmPassword) {
    return res.status(400).json({ message: "Passwords do not match" });
  }

  try {
    
    const existingUser = await UserModel.findOne({ username });

    if (existingUser) {
      return res.status(400).json({ message: "Username already exists" });
    }

    
    const newUser = new UserModel({
      username,
      email,
      branch,
      password
    });

    await newUser.save();
    res.status(201).json({ message: "Signup successful!" });

  } catch (error) {
    res.status(500).json({ message: "Signup failed" });
  }
});


app.post("/login", async (req, res) => {
  const { username, password } = req.body;

  try {
    const user = await UserModel.findOne({ username, password });

    if (!user) {
      return res.status(401).json({ message: "Invalid username or password" });
    }


    const token = jwt.sign({ id: user._id }, "your_jwt_secret_key", {
      expiresIn: "1h" 
    });

    res.status(200).json({
      success: true,
      message: "Login successful!",
      token
    });

  } catch (error) {
    res.status(500).json({ message: "Login failed" });
  }
});


const experienceSchema = new mongoose.Schema({
  username : String,
  name: String,
  company: String,
  role: String,
  duration: String,
  timestamp: String,
});

const Experience = mongoose.model('Experience', experienceSchema);

app.get('/experiences', async (req, res) => {
  try {
    
    const { username } = req.query;

  
    const filter = username ? { username } : {};


    const experiences = await Experience.find(filter);

  
    res.status(200).json(experiences);
  } catch (error) {
    console.error('Error fetching experiences:', error);
    res.status(500).json({ message: 'Error fetching experiences' });
  }
});


app.post('/experiences', async (req, res) => {
  const { username, name, company, role, duration, timestamp } = req.body;

  if (!username || !name || !company || !role || !duration || !timestamp) {
    return res.status(400).json({ message: 'All fields are required.' });
  }

  try {
    const newExperience = new Experience({ username, name, company, role, duration, timestamp });
    const savedExperience = await newExperience.save();
    res.status(201).json(savedExperience);
  } catch (err) {
    res.status(500).send({ error: 'Failed to add experience.' });
  }
});


const projectSchema = new mongoose.Schema({
  username : String,
  department: String,
  domain: String,
  title: String,
  description: String,
  timestamp: String,
});
const Project = mongoose.model("Project", projectSchema);


app.get("/projects", async (req, res) => {
  try {
    const { username } = req.query;
    const filter = username ? { username } : {};
    const projects = await Project.find(filter);
    res.json(projects);
  } catch (error) {
    res.status(500).json({ message: "Error fetching projects" });
  }
});
app.post("/projects", async (req, res) => {
  const { username, department, domain, title, description,timestamp } = req.body;

  if (!username || !department || !domain || !title || !description) {
    return res.status(400).json({ message: "All fields are required" });
  }

  try {
    const newProject = new Project({ username, department, domain, title, description ,timestamp});
    await newProject.save();
    res.status(201).json(newProject);
  } catch (error) {
    res.status(500).json({ message: "Error adding project" });
  }
});

const TechnologySchema = new mongoose.Schema({
  username : String,
  techName: { type: String, required: true },
  description: String,
  difficultyLevel: { type: String, required: true },
  useCases: String,
  paidLinks: [{ type: String, validate: validateURL }],
  unpaidLinks: [{ type: String, validate: validateURL }],
  timestamp: String,
});


function validateURL(value) {
  const urlRegex = /^(https?|ftp):\/\/[^\s/$.?#].[^\s]*$/i;
  return urlRegex.test(value);
}


const Technology = mongoose.model('Technology', TechnologySchema);


app.post('/technologies', async (req, res) => {
  try {
    const { username, techName, description, difficultyLevel, useCases, paidLinks, unpaidLinks, timestamp } = req.body;
    const newTechnology = new Technology({
      username,
      techName,
      description,
      difficultyLevel,
      useCases,
      paidLinks,
      unpaidLinks,
      timestamp,
    });
    await newTechnology.save();
    res.status(200).json({ message: 'Technology added successfully!' });
  } catch (error) {
    res.status(500).json({ message: 'Error adding technology' });
  }
});

app.get('/technologies', async (req, res) => {
  try {
    const { username } = req.query;
    const filter = username ? { username } : {};
    const technologies = await Technology.find(filter); 
    res.status(200).json(technologies);
  } catch (error) {
    res.status(500).json({ message: 'Error fetching technologies' });
  }
});

const QuestionSchema = new mongoose.Schema({
  username: String,
  text: { type: String, required: true },
  answers: [
    {
      text: { type: String, required: true },
      timestamp: { type: String, required: true },
    },
  ],
  timestamp : String,
});

const Question = mongoose.model("Question", QuestionSchema);

app.get("/questions", async (req, res) => {
  try {
    const { username } = req.query;
    const filter = username ? { username } : {};
    const questions = await Question.find(filter);
    res.status(200).json(questions);
  } catch (error) {
    res.status(500).json({ message: "Error fetching questions", error });
  }
});

app.post("/questions", async (req, res) => {
  const { username,text,timestamp } = req.body;

  if (!text) {
    return res.status(400).json({ message: "Question text is required" });
  }

  try {
    const newQuestion = new Question({ username,text, answers: [],timestamp });
    const savedQuestion = await newQuestion.save();
    res.status(201).json(savedQuestion);
  } catch (error) {
    res.status(500).json({ message: "Error saving question", error });
  }
});


app.get("/questions/:id/answers", async (req, res) => {
  const { id } = req.params;

  try {
    const question = await Question.findById(id);

    if (!question) {
      return res.status(404).json({ message: "Question not found" });
    }

    res.status(200).json(question.answers);
  } catch (error) {
    res.status(500).json({ message: "Error fetching answers", error });
  }
});

app.post("/questions/:id/answers", async (req, res) => {
  const { id } = req.params;
  const { username, text,timestamp } = req.body;

  if (!text) {
    return res.status(400).json({ message: "Answer text is required" });
  }

  try {
    const question = await Question.findById(id);

    if (!question) {
      return res.status(404).json({ message: "Question not found" });
    }

    question.answers.push({ username, text, timestamp });
    await question.save();
    res.status(200).json(question.answers);
  } catch (error) {
    res.status(500).json({ message: "Error adding answer", error });
  }
});

const examSchema = new mongoose.Schema({
  title: { type: String, required: true },
  description: { type: String },
  questions: [
    {
      questionText: { type: String, required: true },
      options: { type: [String], required: true },
      correctAnswer: { type: String, required: true },
    },
  ],
});

const resultSchema = new mongoose.Schema({
  userId: { type: String, required: true },
  examId: { type: mongoose.Schema.Types.ObjectId, ref: 'Exam', required: true },
  answers: { type: [String], required: true },
  score: { type: Number, required: true },
});

const Exam = mongoose.model('Exam', examSchema);
const Result = mongoose.model('Result', resultSchema);


app.get('/', (req, res) => {
  res.send('Server is running');
});

app.post('/exams', async (req, res) => {
  try {
    const { title, description, questions } = req.body;

    const newExam = new Exam({
      title,
      description,
      questions,
    });

    await newExam.save();
    res.status(201).json({ message: 'Exam created successfully' });
  } catch (error) {
    console.error('Error creating exam:', error);
    res.status(500).json({ message: 'Failed to create exam' });
  }
});


app.get('/exams', async (req, res) => {
  try {
    const exams = await Exam.find();
    res.status(200).json(exams);
  } catch (error) {
    console.error('Error fetching exams:', error);
    res.status(500).json({ message: 'Failed to fetch exams' });
  }
});


app.post('/results', async (req, res) => {
  const { userId, examId, answers } = req.body;

  if (!userId || !examId || !answers) {
    return res.status(400).json({ message: 'Missing required fields.' });
  }

  if (!mongoose.Types.ObjectId.isValid(examId)) {
    return res.status(400).json({ message: 'Invalid examId format.' });
  }

  try {
    const exam = await Exam.findById(examId);
    if (!exam) {
      return res.status(404).json({ message: 'Exam not found.' });
    }

    const correctAnswers = exam.questions.map(q => q.correctAnswer);
    let score = 0;

    answers.forEach((answer, index) => {
      if (answer === correctAnswers[index]) {
        score += 1; 
      }
    });

    const result = new Result({ userId, examId, answers, score });
    await result.save();

    res.status(200).json({ score });
  } catch (error) {
    console.error("Error while processing exam:", error); 
    res.status(500).json({ message: 'An error occurred while processing the exam.' });
  }
});


app.get('/results', async (req, res) => {
  const { userId } = req.query;

  const results = await Result.find({ userId });
  res.json(results);
});

const adminSchema = new mongoose.Schema({
  username: { type: String, required: true, unique: true },
  password: { type: String, required: true }, 
});

const Admin = mongoose.model("Admin", adminSchema);



app.post("/adminLogin", async (req, res) => {
  const { username, password } = req.body;

  if (!username || !password) {
    return res.status(400).json({ success: false, message: "Please provide both username and password" });
  }

  try {
  
    const admin = await Admin.findOne({ username });

    if (!admin) {
      return res.status(401).json({ success: false, message: "Invalid username or password" });
    }

    const hashedPassword = await bcrypt.hash(admin.password, 10);
    const isPasswordValid = await bcrypt.compare(password, hashedPassword);

    if (!isPasswordValid) {
      return res.status(401).json({ success: false, message: "Invalid username or password" });
    }

    const token = jwt.sign({ id: admin._id, username: admin.username }, "your_jwt_secret_key", {
      expiresIn: "1h", 
    });

    res.status(200).json({
      success: true,
      message: "Admin login successful",
      token,
    });
  } catch (error) {
    console.error("Admin login error:", error);
    res.status(500).json({ success: false, message: "An error occurred during login" });
  }
});

app.get('/leaderboard', async (req, res) => {
  try {
    
    const users = await UserModel.find();

    if (!users || users.length === 0) {
      throw new Error('No users found in the database');
    }

    const leaderboard = await Promise.all(users.map(async (user) => {
      try {
        const experienceCount = await mongoose.connection.collection('experiences').countDocuments({ username: user.username });
        const projectCount = await mongoose.connection.collection('projects').countDocuments({ username: user.username });
        const questionCount = await mongoose.connection.collection('questions').countDocuments({ username: user.username });
        const technologyCount = await mongoose.connection.collection('technologies').countDocuments({ username: user.username });
        const totalScore = await mongoose.connection.collection('results')
         .aggregate([
              { $match: { userId: user.username } }, 
              { $group: { _id: null, totalScore: { $sum: "$score" } } } 
             ])
            .toArray(); 


          const scoreSum = totalScore.length > 0 ? totalScore[0].totalScore : 0;

        return {
          id: user._id,
          name: user.username,
          collections: {
            experiences: experienceCount,
            projects: projectCount,
            questions: questionCount,
            technologies: technologyCount,
            Score : scoreSum
          },
        };
      } catch (err) {
        console.error(`Error counting documents for user ${user.username}:`, err);
        throw err; 
      }
    }));


    leaderboard.sort((a, b) => {
      const totalA = Object.values(a.collections).reduce((sum, count) => sum + count, 0);
      const totalB = Object.values(b.collections).reduce((sum, count) => sum + count, 0);
      return totalB - totalA;
    });

    res.json(leaderboard);
  } catch (err) {
    console.error('Error fetching leaderboard:', err);
    res.status(500).json({ message: 'Error fetching leaderboard', error: err.message });
  }
});





const PORT = process.env.PORT || 5004;
app.listen(PORT, () => {
  console.log(`Server is running on port ${PORT}`);
});