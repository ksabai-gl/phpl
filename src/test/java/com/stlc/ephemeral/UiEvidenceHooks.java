package com.stlc.ephemeral;

import io.cucumber.java.After;
import io.cucumber.java.AfterStep;
import io.cucumber.java.Before;
import io.cucumber.java.Scenario;
import io.github.bonigarcia.wdm.WebDriverManager;
import org.openqa.selenium.OutputType;
import org.openqa.selenium.TakesScreenshot;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.chrome.ChromeDriver;
import org.openqa.selenium.chrome.ChromeOptions;

import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.Paths;
import java.time.Instant;

public class UiEvidenceHooks {
    private static WebDriver driver;
    private int stepNo;

    public static WebDriver getDriver() {
        return driver;
    }

    private Path evidenceRoot() {
        String dir = System.getProperty("stlc.evidence.dir",
                System.getenv().getOrDefault("STLC_EVIDENCE_DIR", "target/stlc-evidence"));
        return Paths.get(dir);
    }

    @Before
    public void setUp() {
        if (driver == null) {
            WebDriverManager.chromedriver().setup();
            ChromeOptions options = new ChromeOptions();
            options.addArguments("--headless=new", "--no-sandbox", "--disable-gpu",
                    "--disable-dev-shm-usage", "--window-size=1280,720");
            driver = new ChromeDriver(options);
        }
        stepNo = 0;
    }

    @AfterStep
    public void afterStep(Scenario scenario) throws IOException {
        if (driver == null) return;
        stepNo++;
        Path shotDir = evidenceRoot().resolve("screenshots");
        Files.createDirectories(shotDir);
        String slug = scenario.getName().replaceAll("[^a-zA-Z0-9-_]", "_");
        Path file = shotDir.resolve(slug + "_" + stepNo + "_" + Instant.now().toEpochMilli() + ".png");
        byte[] png = ((TakesScreenshot) driver).getScreenshotAs(OutputType.BYTES);
        Files.write(file, png);
        scenario.attach(png, "image/png", file.getFileName().toString());
    }

    @After
    public void afterScenario(Scenario scenario) throws IOException {
        if (driver != null && scenario.isFailed()) {
            Path shotDir = evidenceRoot().resolve("screenshots");
            Files.createDirectories(shotDir);
            String slug = scenario.getName().replaceAll("[^a-zA-Z0-9-_]", "_");
            Path file = shotDir.resolve(slug + "_FAIL_" + Instant.now().toEpochMilli() + ".png");
            byte[] png = ((TakesScreenshot) driver).getScreenshotAs(OutputType.BYTES);
            Files.write(file, png);
        }
        stepNo = 0;
    }

    @io.cucumber.java.AfterAll
    public static void tearDownAll() {
        if (driver != null) {
            driver.quit();
            driver = null;
        }
    }
}
