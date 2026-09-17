package com.stlc.ephemeral;

import io.cucumber.junit.Cucumber;
import io.cucumber.junit.CucumberOptions;
import org.junit.runner.RunWith;

@RunWith(Cucumber.class)
@CucumberOptions(
    features = "src/test/resources/features",
    glue = "com.stlc.ephemeral",
    plugin = {"pretty", "summary"},
    monochrome = true
)
public class RunCucumberTest {
}
